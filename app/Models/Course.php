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
        'is_free',
        'is_deleted',
        'teacher_id',
        'subject_id',
    ];
    public function subject()
    {
        return $this->belongsTo( Subject::class)->where('is_deleted', '0');
    }
    public function teacher()
    {
        return $this->belongsTo( Teacher::class);
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
    public function activesubscriptions()
    {
        return $this->hasMany(Subscription::class,'cource_id')->where('is_pending', '0')->whereDate('enddate', '>', Carbon::today());
    }
    public function videos()
    {
        return $this->hasManyThrough(Video::class, Lecture::class);
    }

    public function rates()
    {
        return $this->hasMany(CourseRate::class);
    }
}
