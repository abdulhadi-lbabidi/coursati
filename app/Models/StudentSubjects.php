<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentSubjects extends Model
{
    /** @use HasFactory<\Database\Factories\StudentSubjectsFactory> */
    use HasFactory;


    protected $casts = [
        'subject_id' => 'integer',
        'student_id' => 'integer',
    ];
    protected $fillable = [
        'subject_id',
        'student_id',
    ];
}
