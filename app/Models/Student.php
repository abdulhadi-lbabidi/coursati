<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    /** @use HasFactory<\Database\Factories\StudentFactory> */
    use HasFactory;

    protected $fillable = [
        'name',
        'year_id',
        'is_banned',

    ];

    public function user()
    {
        return $this->morphOne(User::class, 'userable');
    }

    public function adminnotifi()
    {
        return $this->morphMany(Forwardnotification::class, 'notifi');
    }

    public function year()
    {
        return $this->belongsTo( Year::class);
    }

    public function rates()
    {
        return $this->hasMany(CourseRate::class);
    }
    public function favorits()
    {
        return $this->belongsToMany(Teacher::class, 'student_faivorit_teachers')->withTimestamps();
    }
    public function subjects()
    {
        return $this->belongsToMany(Subject::class,'student_subjects');
    }

    public function courses()
    {
        return $this->belongsToMany(Course::class, 'student_faivorit_teachers');
    }

}
