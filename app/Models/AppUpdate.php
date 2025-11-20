<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AppUpdate extends Model
{
    /** @use HasFactory<\Database\Factories\AppUpdateFactory> */
    use HasFactory;

    protected $fillable = [
        'publish_number',
        'publish_date',
        'desc',
    ];
}
