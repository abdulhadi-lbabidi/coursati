<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
    /** @use HasFactory<\Database\Factories\SubscriptionFactory> */
    use HasFactory;

    protected $fillable = [
        'price',
        'enddate',
        'cource_id',
        'courese_type',
        'is_pending',
        'name',
        'desc',
        'tag_name',
        'free_course_description',
        'free_course_image',
        'lectures_number',
        'total_videos_duration',
        'telegram_url',
    ];

    public function students()
    {
        return $this->belongsToMany(Student::class, 'student_subscriptions', 'subscription_id', 'student_id')->withPivotValue('subscription_price');
    }
    public function cource()
    {
        return $this->belongsTo( Course::class);
    }
}
