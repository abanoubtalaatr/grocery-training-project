<?php

namespace App\Filters;

use App\Filters\QueryFilter;


class ReviewFilter extends QueryFilter
{
    public function rating($value)
    {
        $this->builder->where('rating', $value);
    }

    public function meal_id($value)
    {
        $this->builder->where('meal_id', $value);
    }
}