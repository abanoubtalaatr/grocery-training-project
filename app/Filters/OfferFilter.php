<?php

namespace App\Filters;

class OfferFilter extends QueryFilter
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
                'code',
                'like',
                "%{$value}%"
            )
            ->orWhere(
                'description',
                'like',
                "%{$value}%"
            );

        });
    }
}