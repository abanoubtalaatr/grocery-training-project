<?php

namespace App\Traits;

use Illuminate\Http\Request;

trait Filterable
{
    public function scopeFilter($query, $values=[])
    {
        $static = static::getFilterClass();
        return (new $static($values))->apply($query);
    }

    public static function getFilterClass(): string
    {
        return 'App\\Filters\\'.class_basename(static::class).'Filter';
    }
}
