<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class School extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['name', 'slug', 'status', 'email', 'phone', 'logo', 'favicon', 'subscription_package_id'];

    public static function booted()
    {
        static::deleted(function ($school) {
            $school->users()->delete();
            $school->campuses()->delete();
        });

        static::restoring(function ($school) {
            $school->users()->withTrashed()->restore();
            $school->campuses()->withTrashed()->restore();
        });
    }

    public function subscriptionPackage()
    {
        return $this->belongsTo(SubscriptionPackage::class);
    }

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function campuses()
    {
        return $this->hasMany(Campus::class);
    }

    public function mainCampus()
    {
        return $this->hasOne(Campus::class)->where('is_main', true);
    }
}
