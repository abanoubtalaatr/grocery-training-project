<?php
use App\Filters\QueryFilter;

class CategoryFilter extends QueryFilter
{
    
        public function name($value)
        {
            $this->builder->where('name', 'like', '%' . $value . '%');
        }

        
    public function is_active($value)
    {
        $this->builder->where('is_active', $value);
    }

    public function slug($value)
    {
        $this->builder->where('slug', $value);
    }

    public function description($value)
    {
        $this->builder->where('description', 'like', "%$value%");
    }


    
}