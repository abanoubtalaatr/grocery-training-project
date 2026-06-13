<?php

namespace App\Filters;

use App\Filters\QueryFilter;

class CategoryFilter extends QueryFilter
{
    public function name($value)
    {
        $this->builder->where('name', 'like', "%{$value}%");
    }

    public function is_active($value)
    {
        $this->builder->where('is_active', $value);
    }

    public function active_only($value)
    {
        if ($value) {
            $this->builder->where('is_active', true);
        }
    }

    public function sort_order($value)
    {
        $this->builder->orderBy('sort_order', $value);
    }
}
