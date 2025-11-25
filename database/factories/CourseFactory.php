<?php

namespace Database\Factories;

use App\Models\Subject;
use App\Models\Teacher;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Course>
 */
class CourseFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'is_pending'=>fake()->boolean(),
            'is_deleted'=>fake()->boolean(),
            'courese_type'=>fake()->randomElement(['telegram','youtube']),
            'name'=>fake()->name(),
            'desc'=>fake()->text(),
            'course_tag_name'=>fake()->name(),
            'free_course_description'=>fake()->text(),
            'free_course_image'=>fake()->url(),
            'course_image'=>fake()->imageUrl(),
            'lectures_number'=>fake()->numberBetween(1,10),
            'total_videos_duration'=>fake()->numberBetween(1,20),
            'price'=>fake()->numberBetween(10,20),
            'enddate'=>fake()->date(),
            'telegram_url'=>fake()->url(),
            'teacher_id'=>Teacher::all()->random()->id,
            'subject_id'=>Subject::all()->random()->id,
        ];
    }
}
