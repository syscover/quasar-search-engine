<?php namespace Quasar\SearchEngine\Engines\Modes;

use Laravel\Scout\Builder;

class NaturalLanguage extends Mode
{
    public function buildWhereRawString(Builder $builder)
    {
        $queryString = '';

        $queryString .= $this->buildWheres($builder);

        $indexFields = implode(',', config('quasar-search-engine.index_columns'));

        $queryString .= "MATCH($indexFields) AGAINST(? IN NATURAL LANGUAGE MODE";

        if (config('quasar-search-engine.query_expansion')) $queryString .= ' WITH QUERY EXPANSION';

        $queryString .= ')';

        return $queryString;
    }

    public function buildSelectColumns(Builder $builder)
    {
        $indexFields = implode(',', config('quasar-search-engine.index_columns'));

        return "*, MATCH($indexFields) AGAINST(? IN NATURAL LANGUAGE MODE) AS relevance";
    }

    public function buildParams(Builder $builder)
    {
        $this->whereParams[] = $builder->query;

        return $this->whereParams;
    }

    public function isFullText()
    {
        return true;
    }
}