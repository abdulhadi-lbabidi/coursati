<?php

namespace Database\Factories;

use App\Models\Student;
use App\Models\Teacher;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\StudentFaivoritTeacher>
 */
class StudentFaivoritTeacherFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'student_id'=>Student::all()->random()->id,
            'teacher_id'=>Teacher::all()->random()->id,
            'is_favorit'=>fake()->boolean(70),
        ];
    }
}
