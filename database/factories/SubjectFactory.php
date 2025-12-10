<?php

namespace Database\Factories;

use App\Models\Season;
use App\Models\Year;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Subject>
 */
class SubjectFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name'=>fake()->name,
            'doctor_name'=>fake()->name,
            'subject_tag_name'=>fake()->name,
            'subject_nature'=>fake()->randomElement(['writen','automation']),
            'is_deleted'=>fake()->boolean(10),
            'year_id'=>Year::all()->random()->id,
        ];
    }
}
