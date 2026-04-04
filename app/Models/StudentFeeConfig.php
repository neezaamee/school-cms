<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StudentFeeConfig extends Model
{
    protected $fillable = [
        'student_id',
        'fee_plan_id',
        'scholarship_id',
        'transport_fee',
        'hostel_fee',
        'discount_percentage',
        'remarks',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }
}
