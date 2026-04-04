<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FeeCategory extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'school_id',
        'name',
        'description',
        'is_active',
    ];

    public function school()
    {
        return $this->belongsTo(School::class);
    }

    public function structures()
    {
        return $this->hasMany(FeeStructure::class);
    }
}
