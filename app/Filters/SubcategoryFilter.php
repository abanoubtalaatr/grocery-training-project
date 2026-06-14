<?php 

namespace App\Filters;

use App\Filters\QueryFilter;

class SubcategoryFilter extends QueryFilter
{

    public function category_id($value)
    {
        $this->builder->where('category_id', $value);
    }

    public function name($value)
    {
        $this->builder->where('name', 'like', "%$value%");
    }

    public function slug($value)
    {
        $this->builder->where('slug', $value);
    }

    public function description($value)
    {
        $this->builder->where('description', 'like', "%$value%");
    }

    public function is_active($value)
    {
        $this->builder->where('is_active', $value);
    }

    public function order($value)
    {
        $this->builder->where('order', $value);
    }
}