<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\HasSchoolScope;

class Enrollment extends Model
{
    use HasSchoolScope;

    protected $fillable = [
        'student_id',
        'school_id',
        'campus_id',
        'grade_level_id',
        'section_id',
        'class_name', // Legacy support
        'section_name', // Legacy support
        'session_year',
        'is_active',
        'enrollment_date',
    ];

    protected $casts = [
        'enrollment_date' => 'date',
        'is_active' => 'boolean',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function gradeLevel()
    {
        return $this->belongsTo(GradeLevel::class);
    }

    public function section()
    {
        return $this->belongsTo(Section::class);
    }

    public function school()
    {
        return $this->belongsTo(School::class);
    }

    public function campus()
    {
        return $this->belongsTo(Campus::class);
    }
}
