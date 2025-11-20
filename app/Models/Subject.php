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
        'tagname',
        'subject_nature',
        'is_deleted',
        'season_id',
    ];
    public function season()
    {
        return $this->belongsTo( Season::class)->where('is_deleted', '0');
    }

    public function courses()
    {
        return $this->hasMany(Course::class);
    }

}
