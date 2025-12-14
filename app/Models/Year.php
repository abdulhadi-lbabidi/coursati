<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Year extends Model
{
    /** @use HasFactory<\Database\Factories\YearFactory> */
    use HasFactory;

    protected $casts= [
        'number'=>'integer',
        'is_deleted'=>'boolean',
        'university_id'=>'integer',
    ];
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


    public function subjects()
    {
        return $this->hasMany(Subject::class)->where('is_deleted', '0');
    }
    public function subjectsync()
    {
        return $this->hasMany(Subject::class);
    }

}
