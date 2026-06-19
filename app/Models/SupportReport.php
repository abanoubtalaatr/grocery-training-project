<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Concerns\Filterable;

class SupportReport extends Model
{
    use Filterable;
    protected $fillable = [
        'user_id',
        'issue_type',
        'order_number',
        'message',
        'ip_address',
        'user_agent',
        'status',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
