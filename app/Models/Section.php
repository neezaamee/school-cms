<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\HasSchoolScope;

class Section extends Model
{
    use HasSchoolScope;

    protected $fillable = ['school_id', 'grade_level_id', 'campus_id', 'name', 'capacity', 'status'];

    public function gradeLevel()
    {
        return $this->belongsTo(GradeLevel::class);
    }

    public function campus()
    {
        return $this->belongsTo(Campus::class);
    }
}
