<?php

namespace App\Filters;

use App\Filters\QueryFilter;

class OfferFilter extends QueryFilter
{
  public function is_active($value)
  {
    $this->builder->where('is_active', $value);
  }
}
