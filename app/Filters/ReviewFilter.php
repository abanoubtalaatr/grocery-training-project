<?php

namespace App\Filters;

class ReviewFilter extends QueryFilter
{
    public function meal_id($value)
    {
        $this->builder->where('meal_id', $value);
    }

    public function user_id($value)
    {
        $this->builder->where('user_id', $value);
    }

    public function rating($value)
    {
        $this->builder->where('rating', $value);
    }

    public function min_rating($value)
    {
        $this->builder->where('rating', '>=', $value);
    }

    public function is_approved($value)
    {
        $this->builder->where('is_approved', filter_var($value, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE) ?? $value);
    }
}
