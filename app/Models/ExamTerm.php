<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\HasSchoolScope;

class ExamTerm extends Model
{
    use HasSchoolScope;

    protected $fillable = [
        'school_id',
        'name',
        'session_year',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function exams()
    {
        return $this->hasMany(Exam::class);
    }
}
