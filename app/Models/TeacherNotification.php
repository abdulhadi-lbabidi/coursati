<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TeacherNotification extends Model
{
    /** @use HasFactory<\Database\Factories\TeacherNotificationFactory> */
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'is_allowed',
        'university_id',
        'teacher_id',
    ];

    public function university()
    {
        return $this->belongsTo( University::class);
    }

    public function teacher()
    {
        return $this->belongsTo( Teacher::class);
    }
    public function course()
    {
        return $this->belongsTo( Course::class);
    }
}
