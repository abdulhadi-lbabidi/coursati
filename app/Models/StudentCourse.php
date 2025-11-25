<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentCourse extends Model
{
    /** @use HasFactory<\Database\Factories\StudentCourseFactory> */
    use HasFactory;

    protected $fillable = [
        'student_id',
        'course_id',
        'subscription_price',
        'persentage',
    ];
        public function student()
    {
        return $this->belongsTo( Student::class);
    }
        public function course()
    {
        return $this->belongsTo( Course::class);
    }
}
