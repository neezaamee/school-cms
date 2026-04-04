<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Guardian extends Model
{
    protected $table = 'parents';

    protected $fillable = [
        'school_id',
        'father_name',
        'father_phone',
        'father_occupation',
        'mother_name',
        'mother_phone',
        'mother_occupation',
        'guardian_name',
        'guardian_phone',
        'guardian_relation',
        'guardian_email',
    ];

    public function school()
    {
        return $this->belongsTo(School::class);
    }

    public function students()
    {
        return $this->hasMany(Student::class, 'parent_id');
    }
}
