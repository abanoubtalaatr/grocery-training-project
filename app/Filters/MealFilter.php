<?php 

namespace App\Filters;

use App\Filters\QueryFilter;

class MealFilter extends QueryFilter
{
    public function category_id($value)
    {
        $this->builder->where('category_id', $value);
    }

    public function is_available($value)
    {
        $this->builder->where('is_available', $value);
    }

    
    
}