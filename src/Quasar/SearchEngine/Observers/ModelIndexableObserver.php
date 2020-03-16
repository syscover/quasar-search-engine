<?php namespace Quasar\SearchEngine\Observers;

use Quasar\SearchEngine\Models\SearchEngine;

class ModelIndexableObserver
{
    public function created($model)
    {
        dd($model->getUrlPattern());

        //
        SearchEngine::create([
            'indexable_type'    => get_class($model),
            'idexable_uuid'     => $model->uuid,
            'permission_uuid'   => 'xx',
            'permission_uuid'   => $model->toSearchableArray(),
        ]);
    }

    // ??
    public function saved($model)
    {
        //
    }

    public function updated($model)
    {
        //
        dd($model->getUrlPattern());
    }

    public function deleted($model)
    {
        //
    }

    public function restored($model)
    {
        //
    }

    public function forceDeleted($model)
    {
        //
    }
}