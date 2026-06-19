<?php

namespace App\Filters;

class SubcategoryFilter extends QueryFilter
{
    public function search(string $value): void
    {
        $this->builder->where(function ($query) use ($value) {

            $query->where(
                'name',
                'like',
                "%{$value}%"
            );

        });
    }
}