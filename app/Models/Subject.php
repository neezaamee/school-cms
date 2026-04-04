<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\HasSchoolScope;

class Subject extends Model
{
    use HasSchoolScope;

    protected $fillable = ['school_id', 'name', 'code', 'description'];

    public function gradeLevels()
    {
        return $this->belongsToMany(GradeLevel::class, 'subject_grade_level')
            ->withPivot('is_elective')
            ->withTimestamps();
    }
}
