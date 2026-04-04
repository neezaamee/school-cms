<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = [
        'school_id',
        'invoice_id',
        'amount',
        'payment_method',
        'transaction_id',
        'paid_at',
        'received_by',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'paid_at' => 'datetime',
    ];

    public function school()
    {
        return $this->belongsTo(School::class);
    }

    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }

    public function collector()
    {
        return $this->belongsTo(User::class, 'received_by');
    }
}
