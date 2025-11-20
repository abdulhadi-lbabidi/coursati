<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LectureFile extends Model
{
    /** @use HasFactory<\Database\Factories\LectureFileFactory> */
    use HasFactory;
    protected $fillable = [
        'lecture_id',
        'name',
        'size',
        'file_url',
    ];
    public function lecture()
    {
        return $this->belongsTo( Lecture::class);
    }

}
