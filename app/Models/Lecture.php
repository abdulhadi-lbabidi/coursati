<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lecture extends Model
{
    /** @use HasFactory<\Database\Factories\LectureFactory> */
    use HasFactory;

    protected $fillable = [
        'course_id',
        'name',
        'desc',
        'lecture_tag_name',
        'number'
    ];
    public function course()
    {
        return $this->belongsTo( Course::class);
    }
    public function files()
    {
        return $this->hasMany( LectureFile::class);
    }
    public function videos()
    {
        return $this->hasMany( Video::class);
    }
}
