<?php


namespace App\Filters;

use App\Filters\QueryFilter;

class CategoryFilter extends QueryFilter
{
    public function name($value)
    {
        $this->builder->where('name', $value);
    }

    public function slug($value)
    {
        $this->builder->where('slug', $value);
    }

     public function description($value)
    {
        $this->builder->where('description', $value);
    }

     public function is_active($value)
    {
        $this->builder->where('is_active', $value);
    }
     public function sort_order($value)
    {
        $this->builder->where('sort_order', $value);
    }
   

    
}