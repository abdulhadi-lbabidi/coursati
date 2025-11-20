<?php

namespace Database\Factories;

use App\Models\Teacher;
use App\Models\University;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\TeacherNotification>
 */
class TeacherNotificationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title'=>fake()->name,
            'description'=>fake()->name,
            'is_allowed'=>true,
            'university_id'=>University::all()->random()->id,
            'teacher_id'=>Teacher::all()->random()->id,
            'course_id'=>null,
        ];
    }
}
