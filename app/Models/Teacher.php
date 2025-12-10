<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Teacher extends Model
{
    /** @use HasFactory<\Database\Factories\TeacherFactory> */
    use HasFactory;

    protected $fillable = [
        'name',
        'telegram_url',
        'description',
        'gender',
        'image_url',
        'persentage',
        'statue',
        'university_id',
    ];

    public function user()
    {
        return $this->morphOne(User::class, 'userable');
    }

    public function courses()
    {
        return $this->hasMany(Course::class)->where('is_deleted',0)->where('is_pending',0);
    }
    public function withdraws()
    {
        return $this->hasMany(Course::class);
    }
    public function university()
    {
        return $this->belongsTo( University::class);
    }
    public function noteification()
    {
        return $this->hasMany( TeacherNotification::class);
    }

    public function faivorit()
    {
        return $this->belongsToMany(Student::class, 'student_faivorit_teachers')
                ->withTimestamps();
    }
}
