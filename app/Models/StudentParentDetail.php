<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StudentParentDetail extends Model
{
    protected $fillable = [
        'student_id',
        'father_name',
        'father_name_ur',
        'father_profession',
        'mother_name',
        'mother_name_ur',
        'mother_profession',
        'father_cnic',
        'mother_cnic',
        'father_mobile',
        'mother_mobile',
        'father_email',
        'monthly_income',
        'home_address',
        'home_address_ur',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }
}
