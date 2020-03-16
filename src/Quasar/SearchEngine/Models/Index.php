<?php namespace Quasar\SearchEngine\Models;

use Quasar\Core\Models\CoreModel;

/**
 * Class Index
 * @package Quasar\SearchEngine\Models
 */

class Index extends CoreModel
{
    protected $table        = 'search_engine_index';
    protected $fillable     = ['uuid', 'permissionUuid', 'indexableType', 'indexableUuid', 'url', 'title', 'headers', 'strong', 'content'];

    public function indexable()
    {
        return $this->morphTo('indexable', 'indexable_type', 'idexable_uuid', 'uuid');
    }
}
