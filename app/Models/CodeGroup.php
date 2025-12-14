<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CodeGroup extends Model
{
    /** @use HasFactory<\Database\Factories\CodeGroupFactory> */
    use HasFactory;

    protected $casts = [
        'course_id'=>'integer',
        'price'=>'integer',
        'persentage'=>'float',
    ];
    protected $fillable = [
        'course_id',
        'price',
        'persentage',
    ];

    public function course()
    {
        return $this->belongsTo( Course::class,'course_id');
    }
    public function codes()
    {
        return $this->hasMany(Code::class);
    }
}
