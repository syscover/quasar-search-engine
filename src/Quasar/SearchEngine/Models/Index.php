<?php namespace Quasar\SearchEngine\Models;

use Laravel\Scout\Searchable;
use Quasar\Core\Models\CoreModel;

/**
 * Class Index
 * @package Quasar\SearchEngine\Models
 */

class Index extends CoreModel
{
    use Searchable;
    
    protected $table        = 'search_engine_index';
    protected $fillable     = ['uuid', 'permissionUuid', 'indexableType', 'indexableUuid', 'url', 'title', 'contentLayer1', 'contentLayer2', 'contentLayer3'];
    protected $maps         = ['content_layer_1' => 'contentLayer1', 'content_layer_2' => 'contentLayer2', 'content_layer_3' => 'contentLayer3'];

    public function indexable()
    {
        return $this->morphTo('indexable', 'indexable_type', 'idexable_uuid', 'uuid');
    }
}
