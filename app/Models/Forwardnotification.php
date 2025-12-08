<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Forwardnotification extends Model
{
    /** @use HasFactory<\Database\Factories\ForwardnotificationFactory> */
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
    ];

    public function notifi()
    {
        return $this->morphTo();
    }
}
