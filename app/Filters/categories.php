<?php

namespace App\Filters;

class Categories extends QueryFilter
{
   public function name($value)
   {
       $this->builder->where('name', 'like', "%$value%");
   }
   public function description($value)
   {
       $this->builder->where('description', 'like', "%$value%");
   }
}

