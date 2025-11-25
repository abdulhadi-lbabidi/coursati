<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CodeGroup extends Model
{
    /** @use HasFactory<\Database\Factories\CodeGroupFactory> */
    use HasFactory;

    protected $fillable = [
        'statue',
        'code',
    ];

    public function course()
    {
        return $this->belongsTo( Course::class);
    }
    public function codes()
    {
        return $this->hasMany(Code::class);
    }
}
