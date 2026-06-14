<?php

namespace App\Filters;

class CategoryFilter extends QueryFilter
{
    public function slug($value)
    {
        $this->builder->where('slug', $value);
    }
}
