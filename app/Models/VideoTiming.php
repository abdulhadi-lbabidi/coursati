<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VideoTiming extends Model
{
    /** @use HasFactory<\Database\Factories\VideoTimingFactory> */
    use HasFactory;

    protected $casts = [
        'video_id' => 'integer',
        'minutes' => 'integer',
        'secounds' => 'integer',
    ];
    protected $fillable = [
        'name',
        'minutes',
        'secounds',
        'video_id',
    ];


        public function video()
    {
        return $this->belongsTo( Video::class);
    }
}
