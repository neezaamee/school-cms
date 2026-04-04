<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\HasSchoolScope;

class GradeScale extends Model
{
    use HasSchoolScope;

    protected $fillable = [
        'school_id',
        'name',
        'description',
        'is_default',
    ];

    protected $casts = [
        'is_default' => 'boolean',
    ];

    public function details()
    {
        return $this->hasMany(GradeScaleDetail::class)->orderBy('max_score', 'desc');
    }
}
