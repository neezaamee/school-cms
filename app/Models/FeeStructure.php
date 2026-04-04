<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FeeStructure extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'school_id',
        'campus_id',
        'fee_category_id',
        'class_name',
        'amount',
        'due_day',
        'fine_type',
        'fine_amount',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'fine_amount' => 'decimal:2',
    ];

    public function school()
    {
        return $this->belongsTo(School::class);
    }

    public function campus()
    {
        return $this->belongsTo(Campus::class);
    }

    public function category()
    {
        return $this->belongsTo(FeeCategory::class, 'fee_category_id');
    }
}
