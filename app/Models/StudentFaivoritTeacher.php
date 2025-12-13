<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentFaivoritTeacher extends Model
{
    /** @use HasFactory<\Database\Factories\StudentFaivoritTeacherFactory> */
    use HasFactory;

    protected $casts = [
        'student_id' => 'integer',
        'teacher_id' => 'integer',
        'is_favorit' => 'boolean',
    ];
    protected $fillable = [
        'student_id',
        'teacher_id',
        'is_favorit',
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
