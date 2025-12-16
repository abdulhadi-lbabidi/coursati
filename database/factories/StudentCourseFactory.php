<?php

namespace Database\Factories;

use App\Models\Course;
use App\Models\Student;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\StudentCourse>
 */
class StudentCourseFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'persentage'=>fake()->randomFloat(2,0.01,0.3),
            'subscription_price'=>fake()->numberBetween(10,20),
            'subscription_date'=>fake()->date(),
            'student_id'=>Student::all()->random()->id,
            'course_id'=>Course::all()->random()->id,
        ];
    }
}
