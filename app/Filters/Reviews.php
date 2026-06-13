<?php

namespace App\Filters;

class Reviews extends QueryFilter
{
    public function user_id($value)
    {
        $this->builder->where('user_id', $value);
    }
}