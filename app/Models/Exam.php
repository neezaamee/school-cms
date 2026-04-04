<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\HasSchoolScope;

class Exam extends Model
{
    use HasSchoolScope;

    protected $fillable = [
        'school_id',
        'exam_term_id',
        'grade_level_id',
        'subject_id',
        'name',
        'exam_date',
        'total_marks',
        'passing_marks',
        'weightage',
    ];

    protected $casts = [
        'exam_date' => 'date',
        'total_marks' => 'decimal:2',
        'passing_marks' => 'decimal:2',
        'weightage' => 'decimal:2',
    ];

    public function examTerm()
    {
        return $this->belongsTo(ExamTerm::class);
    }

    public function gradeLevel()
    {
        return $this->belongsTo(GradeLevel::class);
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }
}
