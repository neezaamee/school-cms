<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StaffDesignation extends Model
{
    protected $fillable = ['name', 'category', 'slug'];

    public function profiles()
    {
        return $this->hasMany(StaffProfile::class, 'designation_id');
    }
}
