<?php

namespace App\Filters;

class CategoryFilter extends QueryFilter
{
    public function search(string $value): void
    {
        $this->builder->where(
            'name',
            'like',
            "%{$value}%"
        );
    }
}