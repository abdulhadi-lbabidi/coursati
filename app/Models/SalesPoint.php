<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalesPoint extends Model
{
    /** @use HasFactory<\Database\Factories\SalesPointFactory> */
    use HasFactory;

    protected $fillable = [
        'name',
        'phone',
        'address',
        'description',
        'lat',
        'lng',
        'university_id',
    ];

    public function university()
    {
        return $this->belongsTo( University::class);
    }
}
