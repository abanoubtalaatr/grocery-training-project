<?php

namespace App\Filters;

class SubcategoryFilter extends QueryFilter
{
    public function slug($value)
    {
        $this->builder->where('slug', $value);
    }
    public function is_active($value)
    {
        $this->builder->where('is_active', $value);
    }
}
