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
        'number',
        'university_id',
        'is_deleted'
    ];

    public function university()
    {
        return $this->belongsTo( University::class)->where('is_deleted', '0');
    }


}
