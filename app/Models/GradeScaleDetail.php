<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GradeScaleDetail extends Model
{
    protected $fillable = [
        'grade_scale_id',
        'name',
        'min_score',
        'max_score',
        'point',
        'remarks',
    ];

    protected $casts = [
        'min_score' => 'decimal:2',
        'max_score' => 'decimal:2',
        'point' => 'decimal:2',
    ];

    public function gradeScale()
    {
        return $this->belongsTo(GradeScale::class);
    }
}
