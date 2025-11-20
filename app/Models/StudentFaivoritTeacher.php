<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentFaivoritTeacher extends Model
{
    /** @use HasFactory<\Database\Factories\StudentFaivoritTeacherFactory> */
    use HasFactory;

    protected $fillable = [
        'is_favorit',
        'student_id',
        'teacher_id',
    ];

    public function student()
    {
        return $this->belongsTo( Student::class);
    }

    public function teacher()
    {
        return $this->belongsTo( Teacher::class);
    }
}
