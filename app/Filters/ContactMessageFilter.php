<?php

namespace App\Filters;

class ContactMessageFilter extends QueryFilter
{
    public function search(string $value): void
    {
        $this->builder->where(function ($query) use ($value) {

            $query->where(
                'name',
                'like',
                "%{$value}%"
            )
            ->orWhere(
                'email',
                'like',
                "%{$value}%"
            )
            ->orWhere(
                'subject',
                'like',
                "%{$value}%"
            )
            ->orWhere(
                'message',
                'like',
                "%{$value}%"
            );

        });
    }
}