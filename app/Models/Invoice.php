<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Invoice extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'school_id',
        'campus_id',
        'student_id',
        'psid',
        'month',
        'year',
        'due_date',
        'subtotal',
        'fine_amount',
        'total_amount',
        'paid_amount',
        'status',
    ];

    protected $casts = [
        'due_date' => 'date',
        'subtotal' => 'decimal:2',
        'fine_amount' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'paid_amount' => 'decimal:2',
    ];

    public function school()
    {
        return $this->belongsTo(School::class);
    }

    public function campus()
    {
        return $this->belongsTo(Campus::class);
    }

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function items()
    {
        return $this->hasMany(InvoiceItem::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function getBalanceAttribute()
    {
        return $this->total_amount - $this->paid_amount;
    }

    public function getStatusColorAttribute()
    {
        return match($this->status) {
            'Paid' => 'success',
            'Unpaid' => 'danger',
            'Partial' => 'warning',
            'Overdue' => 'dark',
            default => 'secondary',
        };
    }
}
