<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    protected $table = 'attendance';

    protected $fillable = [
        'student_id',
        'enrollment_id',
        'school_id',
        'campus_id',
        'status',
        'attendance_date',
        'remarks',
    ];

    protected $casts = [
        'attendance_date' => 'date',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function enrollment()
    {
        return $this->belongsTo(Enrollment::class);
    }

    public function school()
    {
        return $this->belongsTo(School::class);
    }

    public function campus()
    {
        return $this->belongsTo(Campus::class);
    }

    /**
     * Get the badge color class for the attendance status.
     */
    public function getStatusColorAttribute()
    {
        return match ($this->status) {
            'Present' => 'success',
            'Absent' => 'danger',
            'Late' => 'warning',
            'Half Day' => 'info',
            default => 'secondary',
        };
    }
}
