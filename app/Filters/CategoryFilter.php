<?php

namespace App\Filters;

class CategoryFilter extends QueryFilter
{
    public function name($value)
    {
        return $this->builder->where('name', 'like', '%' . $value . '%');
    }
}