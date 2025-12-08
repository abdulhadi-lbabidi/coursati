<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Video extends Model
{
    /** @use HasFactory<\Database\Factories\VideoFactory> */
    use HasFactory;

    protected $fillable = [
        'name',
        'desc',
        'video_url',
        'views',
        'is_free',
        'lecture_id',
        'video_tag_name',
        'number'
    ];
    public function lecture()
    {
        return $this->belongsTo( Lecture::class);
    }
    public function timing()
    {
        return $this->hasMany( VideoTiming::class)->limit(1);
    }
}
