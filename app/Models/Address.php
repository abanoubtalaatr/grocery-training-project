<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Address extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'label',
        'full_name',
        'phone',
        'country_code',
        'street_address',
        'building_number',
        'floor',
        'apartment',
        'landmark',
        'city',
        'state',
        'postal_code',
        'country',
        'notes',
        'is_default',
        'latitude',
        'longitude',
    ];

    protected $casts = [
        'is_default' => 'boolean',
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * عنوان مختصر يستخدم في الجداول والقوائم.
     */
    public function getShortAddressAttribute(): string
    {
        return trim("{$this->street_address}, {$this->city}" . ($this->state ? ", {$this->state}" : ''));
    }
}