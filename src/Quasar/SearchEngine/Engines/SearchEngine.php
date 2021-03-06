<?php namespace Quasar\SearchEngine\Engines;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Laravel\Scout\Builder;
use Laravel\Scout\Engines\Engine;
use Quasar\SearchEngine\Engines\Modes\ModeContainer;
use Quasar\SearchEngine\Models\Index;
use Quasar\SearchEngine\Services\IndexService;

class SearchEngine extends Engine
{
    protected $mode;

    protected $fallbackMode;

    public function __construct(ModeContainer $modeContainer)
    {
        $this->mode         = $modeContainer->mode;
        $this->fallbackMode = $modeContainer->fallbackMode;
    }

    public function update($models)
    {
        // get current index from models records
        $modelsIndexed  = Index::whereIn('indexable_uuid', $models->pluck('uuid'))->get();
        $indexService   = new IndexService;
        
        foreach ($models as $model)
        {
            // avoid index itself
            if (get_class($model) !== 'Quasar\SearchEngine\Models\Index')
            {
                // find existing index
                $modelIndexed = $modelsIndexed->where('indexableUuid', $model->uuid)->first();
                if ($modelIndexed)
                {
                    $indexService->update($model->toIndexableArray(), $modelIndexed->uuid);
                }
                else
                {
                    $indexService->create($model->toIndexableArray());
                }
            }
        }
    }

    /**
     * Remove the given model from the index.
     *
     * @param  \Illuminate\Database\Eloquent\Collection  $models
     * @return void
     */
    public function delete($models)
    {
        Index::whereIn('indexable_uuid', $models->pluck('uuid'))->delete();
    }

    /**
     * Pluck and return the primary keys of the given results.
     *
     * @param  mixed  $results
     * @return \Illuminate\Support\Collection
     */
    public function mapIds($results)
    {
        return collect($results['results'])->map(function ($result) {
            return $result->uuid;
        });
    }

    /**
     * Perform the given search on the engine.
     *
     * @param  \Laravel\Scout\Builder  $builder
     * @return mixed
     */
    public function search(Builder $builder)
    {
        $result = [];

        if ($this->shouldNotRun($builder)) 
        {
            $result['results']  = Collection::make();
            $result['count']    = 0;

            return $result;
        }

        $mode = $this->shouldUseFallback($builder) ? $this->fallbackMode : $this->mode;

        $whereRawString = $mode->buildWhereRawString($builder);
        $params = $mode->buildParams($builder);

        $model = $builder->model;
        $query = $model::whereRaw($whereRawString, $params);
        
        if ($mode->isFullText()) 
        {
            $query = $query->selectRaw(DB::raw($mode->buildSelectColumns($builder)), $params);
        }

        if($builder->callback)
        {
            $query = call_user_func($builder->callback, $query, $this);
        }

        $result['count'] = $query->count();

        if (property_exists($builder, 'orders') && !empty($builder->orders)) {
            foreach ($builder->orders as $order) {
                $query->orderBy($order['column'], $order['direction']);
            }
        }

        if ($builder->limit) $query = $query->take($builder->limit);

        if (property_exists($builder, 'offset') && $builder->offset) 
        {
            $query = $query->skip($builder->offset);
        }

        $result['results'] = $query->get();

        return $result;
    }

    /**
     * Perform the given search on the engine.
     *
     * @param Builder $builder
     * @param int     $perPage
     * @param int     $page
     *
     * @return mixed
     */
    public function paginate(Builder $builder, $perPage, $page)
    {
        $builder->limit = $perPage;
        $builder->offset = ($perPage * $page) - $perPage;

        return $this->search($builder);
    }

    /**
     * Map the given results to instances of the given model.
     *
     * @param Laravel\Scout\Builder               $builder
     * @param \Illuminate\Database\Eloquent\Model $model
     *
     * @return Collection
     */
    public function map(Builder $builder, $results, $model)
    {
        return $results['results'];
    }

    /**
     * Get the total count from a raw result returned by the engine.
     *
     * @param mixed $results
     *
     * @return int
     */
    public function getTotalCount($results)
    {
        return $results['count'];
    }

    /**
     * Flush all of the model's records from the engine.
     *
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * 
     * @return void
     */
    public function flush($model) 
    {
        Index::whereIn('indexable_type', get_class($model))->delete();
    }

    protected function shouldNotRun($builder)
    {
        return strlen($builder->query) < config('quasar-search-engine.min_search_length');
    }

    protected function shouldUseFallback($builder)
    {
        return $this->mode->isFullText() && 
            strlen($builder->query) < config('quasar-search-engine.min_fulltext_search_length');
    }
}