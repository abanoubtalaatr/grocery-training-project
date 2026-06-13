<?php

namespace App\Filters;

class CategoryFilter extends QueryFilter
{
    public function is_active($value)
    {
        $this->builder->where('is_active', filter_var($value, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE) ?? $value);
    }

    public function slug($value)
    {
        $this->builder->where('slug', $value);
    }

    public function name($value)
    {
        $this->builder->where('name', 'like', "%{$value}%");
    }

    public function sort_order($value)
    {
        $this->builder->where('sort_order', $value);
    }
}
