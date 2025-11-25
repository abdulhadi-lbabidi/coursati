<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contactinfo extends Model
{
    /** @use HasFactory<\Database\Factories\ContactinfoFactory> */
    use HasFactory;
    protected $fillable = [
        'phone',
        'email',
        'insta_url',
        'facebook_url',
        'telegram_url',
    ];


}
