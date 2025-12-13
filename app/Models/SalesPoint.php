<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalesPoint extends Model
{
    /** @use HasFactory<\Database\Factories\SalesPointFactory> */
    use HasFactory;

    protected $casts = [
        'university_id' => 'integer',
        'is_deleted' => 'boolean',
        'lng' => 'decimal',
        'lat' => 'decimal',
    ];
    protected $fillable = [
        'name',
        'phone',
        'address',
        'description',
        'percentage',
        'lat',
        'lng',
        'image_url',
        'university_id',
    ];

    public function university()
    {
        return $this->belongsTo( University::class);
    }
}
