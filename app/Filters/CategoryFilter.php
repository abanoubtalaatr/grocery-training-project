<?php

namespace App\Filters;

class CategoryFilter extends QueryFilter
{
    public function name($value) 
    {
        $this->builder->where('name', 'like', '%'.$value.'%');
    }

    public function is_active($value) 
    {
        $this->builder->where('is_active', $value);
    }
}
