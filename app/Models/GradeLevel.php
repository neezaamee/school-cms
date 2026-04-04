<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\HasSchoolScope;

class GradeLevel extends Model
{
    use HasSchoolScope;

    protected $fillable = ['school_id', 'name', 'code', 'status'];

    public function sections()
    {
        return $this->hasMany(Section::class);
    }

    public function subjects()
    {
        return $this->belongsToMany(Subject::class, 'subject_grade_level')
            ->withPivot('is_elective')
            ->withTimestamps();
    }
}
