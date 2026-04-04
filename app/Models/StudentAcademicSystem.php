<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StudentAcademicSystem extends Model
{
    protected $fillable = [
        'student_id',
        'board_type',
        'medium',
        'previous_class',
        'previous_school',
        'last_result_grade',
        'subjects_selected',
        'academic_group',
        'shift',
    ];

    protected $casts = [
        'subjects_selected' => 'array',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }
}
