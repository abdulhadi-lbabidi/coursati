<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Season extends Model
{
    /** @use HasFactory<\Database\Factories\SeasonFactory> */
    use HasFactory;

        protected $fillable = [
        'name',
        'season_num',
        'year_id',
        'is_deleted'
    ];

    public function year()
    {
        return $this->belongsTo( Year::class)->where('is_deleted', '0');
    }
    public function subjects()
    {
        return $this->hasMany(Subject::class);
    }


}
