<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class Course extends Model
{
    /** @use HasFactory<\Database\Factories\CourseFactory> */
    use HasFactory;

    protected $fillable = [
        'is_deleted',
        'is_pending',
        'courese_type',
        'name',
        'desc',
        'course_tag_name',
        'free_course_description',
        'free_course_image',
        'lectures_number',
        'total_videos_duration',
        'price',
        'enddate',
        'telegram_url',
        'teacher_id',
        'subject_id',
    ];
    public function subject()
    {
        return $this->belongsTo( Subject::class)->where('is_deleted',0);
    }
    public function teacher()
    {
        return $this->belongsTo( Teacher::class)->where('statue','!=','deleted')->where('statue','!=','pending');
    }
    public function year()
    {
        return $this->hasOneThrough(Year::class, Subject::class, 'id', 'id', 'subject_id', 'season_id')
            ->join('seasons', 'seasons.id', '=', 'subjects.season_id')
            ->whereColumn('years.id', 'seasons.year_id');
    }
    public function lectures()
    {
        return $this->hasMany(Lecture::class);
    }
    public function videos()
    {
        return $this->hasManyThrough(Video::class, Lecture::class);
    }

    public function rates()
    {
        return $this->hasMany(CourseRate::class);
    }

        public function students()
    {
        return $this->belongsToMany(Student::class, 'student_courses', 'course_id', 'student_id')->withPivotValue('subscription_price');
    }

    public function codegroup()
    {
        return $this->hasMany(CodeGroup::class);
    }
    public function subscriptions()
    {
        return $this->hasMany(StudentCourse::class);
    }
}
