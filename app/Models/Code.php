<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Code extends Model
{
    /** @use HasFactory<\Database\Factories\CodeFactory> */
    use HasFactory;

    protected $fillable = [
        'statue',
        'code',
    ];
    public function codeGroup()
    {
        return $this->belongsTo( CodeGroup::class);
    }
}
