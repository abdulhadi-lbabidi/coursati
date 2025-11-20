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
    public function university()
    {
        return $this->belongsTo( University::class);
    }
    public function year()
    {
        return $this->belongsTo( Year::class);
    }
    public function subscribtions()
    {
        return $this->belongsToMany(Subscription::class, 'student_subscriptions', 'student_id', 'subscription_id')->withPivotValue('subscription_price');
    }
    public function rates()
    {
        return $this->hasMany(CourseRate::class);
    }
    public function favorits()
    {
        return $this->hasMany(StudentFaivoritTeacher::class);
    }
    public function subjects()
    {
        return $this->belongsToMany(Subject::class,'student_subjects');
    }

}
