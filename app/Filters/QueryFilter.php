<?php

namespace App\Filters;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;

abstract class QueryFilter
{
    protected $values = [];
    protected $builder;

    public function __construct($values = [])
    {
        $this->values = $values;
    }

    public function apply(Builder $builder): Builder
    {
        $this->builder = $builder;
        foreach ($this->filterableParameters() as $name => $value) {
            $this->{$name}($value);
        }
        return $this->builder;
    }

    
    protected function filterableParameters(): array
    {
        return collect($this->values)
            ->filter(fn($value, string $key) => method_exists($this, $key))
            ->mapWithKeys(fn($value, string $key) => [$key => $value])
            ->all();
    }
}