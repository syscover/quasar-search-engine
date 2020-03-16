<?php namespace Quasar\SearchEngine\Engines\Modes;

use Laravel\Scout\Builder;

abstract class Mode
{
    protected $whereParams = [];

    abstract public function buildWhereRawString(Builder $builder);

    abstract public function buildParams(Builder $builder);

    abstract public function isFullText();

    protected function buildWheres(Builder $builder)
    {
        $this->whereParams = null;

        $queryString = '';

        // parse string to array
        $parsedWheres = $this->parseWheres($builder->wheres);

        foreach ($parsedWheres as $parsedWhere) 
        {
            $field      = $parsedWhere[0];
            $operator   = $parsedWhere[1];
            $value      = $parsedWhere[2];

            if ($value !== null) 
            {
                $this->whereParams[$field] = $value;
                $queryString .= "$field $operator ? AND ";
            } 
            else 
            {
                $queryString .= "$field IS NULL AND ";
            }
        }

        return $queryString;
    }

    private function parseWheres($wheres)
    {
        $pattern = '/([A-Za-z_]+[A-Za-z_0-9]?)[ ]?(<>|!=|=|<=|<|>=|>)/';

        $result = array();
        foreach ($wheres as $field => $value) 
        {
            preg_match($pattern, $field, $matches);
            $result [] = !empty($matches) ? array($matches[1], $matches[2], $value) : array($field, '=', $value);
        }

        return $result;
    }
}
