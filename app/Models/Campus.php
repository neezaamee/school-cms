<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Campus extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name', 
        'school_id', 
        'country_id', 
        'city_id', 
        'address', 
        'slug', 
        'is_main'
    ];

    public function school()
    {
        return $this->belongsTo(School::class);
    }

    public function country()
    {
        return $this->belongsTo(Country::class);
    }

    public function city()
    {
        return $this->belongsTo(City::class);
    }

    public function users()
    {
        return $this->hasMany(User::class);
    }

    /**
     * Check if the campus has low activity.
     * Criteria: Created > 6 months ago AND has < 20 users (students/staff).
     */
    public function isLowActivity(): bool
    {
        if ($this->created_at->diffInMonths(now()) < 6) {
            return false;
        }

        return $this->users()->count() < 20;
    }

    public function getActivityStatusAttribute(): array
    {
        $count = $this->users()->count();
        $isLow = $this->isLowActivity();
        
        return [
            'count' => $count,
            'is_low' => $isLow,
            'message' => $isLow ? "Low activity detected (< 20 users after 6 months)" : "Normal activity"
        ];
    }
}
