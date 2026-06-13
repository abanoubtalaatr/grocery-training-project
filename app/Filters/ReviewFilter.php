<?php 

namespace App\Filters;

use App\Filters\QueryFilter;

class ReviewFilter extends QueryFilter
{

   public function user_id($value)
    {
        $this->builder->where('user_id', $value);
    }

    public function meal_id($value)
    {
        $this->builder->where('meal_id', $value);
    }
    public function rating($value)
    {
        $this->builder->where('rating', $value);
    }

    public function is_approved($value)
    {
        $this->builder->where('is_approved', $value);
    }

    
    
}