<?php

namespace Database\Factories;

use App\Models\Subject;
use App\Models\Teacher;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

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
        $randomDate = Carbon::now()->subYear()->addDays(rand(0, 5 * 365));
        return [
            'is_pending'=>fake()->boolean(10),
            'is_deleted'=>fake()->boolean(10),
            'courese_type'=>fake()->randomElement(['telegram','youtube']),
            'name'=>fake()->name(),
            'desc'=>fake()->text(),
            'course_tag_name'=>fake()->name(),
            'free_course_description'=>fake()->text(),
            'free_course_image'=>fake()->url(),
            'lectures_number'=>fake()->numberBetween(1,10),
            'total_videos_duration'=>fake()->numberBetween(1,20),
            'price'=>fake()->numberBetween(10000,100000),
            'enddate'=>$randomDate->format('Y-m-d'),
            'telegram_url'=>fake()->url(),
            'teacher_id'=>Teacher::all()->random()->id,
            'subject_id'=>Subject::all()->random()->id,
        ];
    }
}
