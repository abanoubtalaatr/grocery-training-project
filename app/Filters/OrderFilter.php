<?php

namespace App\Filters;

class OrderFilter extends QueryFilter
{
    public function search(string $value): void
    {
        $this->builder->where(function ($query) use ($value) {

            $query->where(
                'order_number',
                'like',
                "%{$value}%"
            )
            ->orWhereHas('user', function ($userQuery) use ($value) {

                $userQuery
                    ->where(
                        'firstname',
                        'like',
                        "%{$value}%"
                    )
                    ->orWhere(
                        'lastname',
                        'like',
                        "%{$value}%"
                    )
                    ->orWhere(
                        'email',
                        'like',
                        "%{$value}%"
                    );

            });

        });
    }
}