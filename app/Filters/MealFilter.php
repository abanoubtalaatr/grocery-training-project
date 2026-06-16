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

     public function meal_id($value)
     {
        $this->builder->where('meal_id',$value);
     }

      public function rating($value)
     {
        $this->builder->where('rating',$value);
     }

      public function comment($value)
     {
        $this->builder->where('comment',$value);
     }

      public function is_approved($value)
     {
        $this->builder->where('is_approved',$value);
     }
   
        
}