<?php

namespace App\Filters;

use App\Filters\QueryFilter;

class OrderFilter extends QueryFilter
{
    public function status($value)
    {
        $this->builder->where('status', $value);
    }

    public function user_id($value)
    {
        $this->builder->where('user_id', $value);
    }

}