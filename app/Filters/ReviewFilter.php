<?php

namespace App\Filters;

class ReviewFilter extends QueryFilter
{
   /**
    * Filter by meal id
    */
   public function meal_id($value) 
   {
    $this->builder->where('meal_id', $value);
   }

   /**
    * Filter by user id
    */
   public function user_id($value) 
   {
    $this->builder->where('user_id', $value);
   }

   /**
    * Filter by rating
    */
   public function rating($value) 
   {
    $this->builder->where('rating', $value);
   }

   /**
    * Filter by approved status
    */
   public function is_approved($value) 
   {
    $this->builder->where('is_approved', $value);
   }
   
}
