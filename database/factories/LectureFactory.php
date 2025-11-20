<?php

namespace Database\Factories;

use App\Models\Course;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Lecture>
 */
class LectureFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name'=>fake()->randomElement(['المحاضرة الأولى','المحاضرة الثانية','المحاضرة الثالثة','المحاضرة الرابعة']),
            'desc'=>fake()->text(300),
            'course_id'=>Course::all()->random()->id,
        ];
    }
}
