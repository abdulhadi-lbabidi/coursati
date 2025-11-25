<?php

namespace Database\Factories;

use App\Models\Lecture;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\LectureFile>
 */
class LectureFileFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name'=>fake()->name(),
            'file_url'=>fake()->url(),
            'size'=>fake()->randomFloat(2,1,15),
            'number'=>fake()->randomNumber(1,10),
            'lecture_id'=>Lecture::all()->random()->id,
        ];
    }
}
