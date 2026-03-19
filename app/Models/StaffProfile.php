<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StaffProfile extends Model
{
    protected $fillable = [
        'user_id', 
        'school_id',
        'name',
        'email',
        'campus_id', 
        'designation_id', 
        'phone', 
        'emergency_phone', 
        'education_record', 
        'profile_photo', 
        'address'
    ];

    public function school()
    {
        return $this->belongsTo(School::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function campus()
    {
        return $this->belongsTo(Campus::class);
    }

    public function designation()
    {
        return $this->belongsTo(StaffDesignation::class, 'designation_id');
    }
}
