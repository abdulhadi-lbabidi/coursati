<?php

namespace Database\Factories;

use App\Models\Course;
use App\Models\Student;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\CourseRate>
 */
class CourseRateFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'stars'=>fake()->randomFloat(1,1,5),
            'course_id'=>Course::all()->random()->id,
            'student_id'=>Student::all()->random()->id
        ];
    }
}
