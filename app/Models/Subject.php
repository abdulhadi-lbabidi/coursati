<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    /** @use HasFactory<\Database\Factories\SubjectFactory> */
    use HasFactory;

    protected $fillable = [
        'name',
        'doctor_name',
        'subject_tag_name',
        'subject_nature',
        'is_deleted',
        'year_id',
    ];
    public function year()
    {
        return $this->belongsTo( Year::class)->where('is_deleted', '0');
    }

    public function courses()
    {
        return $this->hasMany(Course::class)->where('is_deleted', '0')->where('is_pending', '0');
    }

    public function teachers() {
        return $this->belongsToMany(Teacher::class);
    }


}
