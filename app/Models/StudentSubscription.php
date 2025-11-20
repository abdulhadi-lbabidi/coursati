<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentSubscription extends Model
{
    /** @use HasFactory<\Database\Factories\StudentSubscriptionFactory> */
    use HasFactory;

    protected $fillable = [
        'student_id',
        'subscription_id',
        'subscription_price',
    ];
        public function student()
    {
        return $this->belongsTo( Student::class);
    }
        public function subscription()
    {
        return $this->belongsTo( Subscription::class);
    }
}
