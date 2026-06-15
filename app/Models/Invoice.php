<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    //
         protected $fillable = [
        'user_id',
        'total',
        'status',
        'pdf_path',
    ];
    public function items()
{
    return $this->hasMany(InvoiceItem::class);
}

public function user()
{
    return $this->belongsTo(User::class);
}
}
