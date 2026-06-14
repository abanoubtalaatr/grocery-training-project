<?php

namespace App\Filters;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;

abstract class QueryFilter
{
    protected $request;
    protected $builder;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function apply(Builder $builder): Builder
    {
        $this->builder = $builder;
        foreach ($this->filterableParameters() as $name => $value) {
            $this->{$name}($value); // category_id(5)
        }
        return $this->builder;
    }

    
    protected function filterableParameters(): array
    {
        return collect($this->request->keys()) // category_id , is_available
            ->filter(fn(string $key) => method_exists($this, $key))
            ->mapWithKeys(fn(string $key) => [$key => $this->request->input($key)]) //'category_id' => 2
            ->all();
    }
}
