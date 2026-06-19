<?php

namespace App\Filters;

class ReviewFilter extends QueryFilter
{
    public function search(string $value): void
    {
        $this->builder->where(function ($query) use ($value) {

            $query->where(
                'comment',
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

            })

            ->orWhereHas('meal', function ($mealQuery) use ($value) {

                $mealQuery->where(
                    'title',
                    'like',
                    "%{$value}%"
                );

            });

        });
    }
}