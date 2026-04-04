<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\HasSchoolScope;

class ExamMark extends Model
{
    use HasSchoolScope;

    protected $fillable = [
        'school_id',
        'exam_id',
        'student_id',
        'marks_obtained',
        'is_absent',
        'remarks',
        'created_by',
    ];

    protected $casts = [
        'marks_obtained' => 'decimal:2',
        'is_absent' => 'boolean',
    ];

    public function exam()
    {
        return $this->belongsTo(Exam::class);
    }

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
