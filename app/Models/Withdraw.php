<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Withdraw extends Model
{
    /** @use HasFactory<\Database\Factories\WithdrawFactory> */
    use HasFactory;

    protected $casts = [
        'teacher_id' => 'integer',
        'amount' => 'integer',
        'withdraw_date' => 'date',
    ];

    protected $fillable = [
        'amount',
        'withdraw_date',
        'teacher_id',
    ];

    public function teacher()
    {
        return $this->belongsTo( Teacher::class);
    }
}
