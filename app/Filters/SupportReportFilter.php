<?php

namespace App\Filters;

class SupportReportFilter extends QueryFilter
{
    public function search(string $value): void
    {
        $this->builder->where(function ($query) use ($value) {

            $query->where(
                'issue_type',
                'like',
                "%{$value}%"
            )
            ->orWhere(
                'order_number',
                'like',
                "%{$value}%"
            )
            ->orWhere(
                'message',
                'like',
                "%{$value}%"
            )
            ->orWhereHas('user', function ($userQuery) use ($value) {

                $userQuery
                    ->where('firstname', 'like', "%{$value}%")
                    ->orWhere('lastname', 'like', "%{$value}%")
                    ->orWhere('email', 'like', "%{$value}%");

            });

        });
    }
}