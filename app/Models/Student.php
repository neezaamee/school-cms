<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\SoftDeletes;

class Student extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'school_id',
        'campus_id',
        'parent_id',
        'admission_no',
        'b_form',
        'roll_no',
        'first_name',
        'first_name_ur',
        'last_name',
        'last_name_ur',
        'dob',
        'gender',
        'blood_group',
        'religion',
        'nationality',
        'is_hafiz_e_quran',
        'is_pwd',
        'medical_notes',
        'category',
        'address',
        'phone',
        'photo',
        'status',
    ];

    protected $casts = [
        'dob' => 'date',
        'is_hafiz_e_quran' => 'boolean',
        'is_pwd' => 'boolean',
    ];

    public function parentDetails()
    {
        return $this->hasOne(StudentParentDetail::class);
    }

    public function guardianDetails()
    {
        return $this->hasOne(StudentGuardianDetail::class);
    }

    public function academicSystem()
    {
        return $this->hasOne(StudentAcademicSystem::class);
    }

    public function feeConfig()
    {
        return $this->hasOne(StudentFeeConfig::class);
    }

    public function attachments()
    {
        return $this->hasMany(StudentAttachment::class);
    }

    public function school()
    {
        return $this->belongsTo(School::class);
    }

    public function campus()
    {
        return $this->belongsTo(Campus::class);
    }

    public function guardian()
    {
        return $this->belongsTo(Guardian::class, 'parent_id');
    }

    public function enrollments()
    {
        return $this->hasMany(Enrollment::class);
    }

    public function attendance()
    {
        return $this->hasMany(Attendance::class);
    }

    /**
     * Get the student's full name.
     */
    public function getFullNameAttribute()
    {
        return "{$this->first_name} {$this->last_name}";
    }
}
