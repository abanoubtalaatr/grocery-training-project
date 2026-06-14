<?php

namespace App\Filters;

class MealFilter extends QueryFilter
{
    public function is_available($value)
    {
        $this->builder->where('is_available', $value);
    }

    public function category_id($value)
    {
        $this->builder->where('category_id', $value);
    }
}
