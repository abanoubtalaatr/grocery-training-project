<?php

namespace App\Filters;

class MealFilter extends QueryFilter
{
    public function search(string $value): void
    {
        $this->builder->where(function ($query) use ($value) {

            $query->where(
                'title',
                'like',
                "%{$value}%"
            )
            ->orWhere(
                'brand',
                'like',
                "%{$value}%"
            );

        });
    }
}