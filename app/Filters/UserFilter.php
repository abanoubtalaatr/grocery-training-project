<?php

namespace App\Filters;

class UserFilter extends QueryFilter
{
    public function search(string $value): void
    {
        $this->builder->where(function ($query) use ($value) {

            $query->where('username', 'like', "%{$value}%")
                ->orWhere('firstname', 'like', "%{$value}%")
                ->orWhere('lastname', 'like', "%{$value}%")
                ->orWhere('email', 'like', "%{$value}%");

        });
    }
}