<?php

namespace App\Filters;
use App\Filters\QueryFilter;
class CategoryFilter extends QueryFilter
{
    public function name($value)
    {
        $this->builder->where('name', 'like', "%$value%");
    }

    public function is_available($value)
    {
        $this->builder->where('is_available', $value);
    }
}