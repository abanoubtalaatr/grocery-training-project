<?php 

namespace App\Filters;

use App\Filters\QueryFilter;

class ReviewFilter extends QueryFilter
{
    public function rating($value)
    {
        $this->builder->where('rating', $value);
    }
    public function ratingAbove($value)
    {
        $this->builder->where('rating', '>', $value);
    }
    public function is_approved($value)
    {
        $this->builder->where('is_approved', $value);
    }   
    

    
}