<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StudentAttachment extends Model
{
    protected $fillable = [
        'student_id',
        'attachment_type',
        'file_path',
        'file_name',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }
}
