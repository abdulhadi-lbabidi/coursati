<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Year extends Model
{
    /** @use HasFactory<\Database\Factories\YearFactory> */
    use HasFactory;

    protected $fillable = [
        'number',
        'name',
        'is_deleted',
        'university_id',
    ];
    public function university()
    {
        return $this->belongsTo( University::class);
    }

    public function seasons()
    {
        return $this->hasMany(Season::class)->where('is_deleted', '0');
    }
    public function subjects()
    {
        return $this->hasManyThrough(Subject::class, Season::class)->where('seasons.is_deleted', '0');
    }
    public function subjectsync()
    {
        return $this->hasManyThrough(Subject::class, Season::class);
    }

}
