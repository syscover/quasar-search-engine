<?php namespace Quasar\SearchEngine\Traits;

use Quasar\SearchEngine\Observers\ModelIndexableObserver;

trait Indexable
{
    public static function bootIndexable()
    {
        static::observe(new ModelIndexableObserver);
    }
}
