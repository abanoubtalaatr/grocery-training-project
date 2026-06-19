<?php

namespace App\Filters;

class FaqFilter extends QueryFilter
{
    public function search(string $value): void
    {
        $this->builder->where(function ($query) use ($value) {

            $query->where(
                'question',
                'like',
                "%{$value}%"
            )
            ->orWhere(
                'answer',
                'like',
                "%{$value}%"
            )
            ->orWhere(
                'category',
                'like',
                "%{$value}%"
            );

        });
    }
}