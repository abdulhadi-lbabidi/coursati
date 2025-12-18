<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubjectTeachers extends Model
{
    /** @use HasFactory<\Database\Factories\SubjectTeachersFactory> */
    use HasFactory;

     protected $casts = [
        'subject_id' => 'integer',
        'teacher_id' => 'integer',
    ];
    protected $fillable = [
        'subject_id',
        'teacher_id',
    ];
}
