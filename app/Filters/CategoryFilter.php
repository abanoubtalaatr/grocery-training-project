<?php

namespace App\Filters;

use App\Filters\QueryFilter;

class CategoryFilter extends QueryFilter
{
    public function name($value)
    {
        $this->builder->where('name', $value);
    }

    public function is_active($value)
    {
        $this->builder->where('is_active', $value);
    }
}
