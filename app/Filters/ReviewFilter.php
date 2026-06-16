<?php

namespace App\Filters;

use App\Filters\QueryFilter;

class ReviewFilter extends QueryFilter
{
    public function user_id($value)
    {
        $this->builder->where('user_id', $value);
    }

    
}