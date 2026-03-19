<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SubscriptionPackage extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name', 
        'student_limit', 
        'staff_limit', 
        'entry_limit', 
        'price', 
        'is_active',
        'has_tech_support'
    ];

    public function schools()
    {
        return $this->hasMany(School::class);
    }
}
