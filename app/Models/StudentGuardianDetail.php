<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StudentGuardianDetail extends Model
{
    protected $fillable = [
        'student_id',
        'name',
        'name_ur',
        'relation',
        'cnic',
        'mobile',
        'address',
        'address_ur',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }
}
