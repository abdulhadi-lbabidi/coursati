<?php

namespace Database\Factories;

use App\Models\University;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Teacher>
 */
class TeacherFactory extends Factory
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
            'description'=>fake()->text(),
            'statue'=>fake()->randomElement(['banned','active']),
            'gender'=>fake()->randomElement(['ذكر','أنثى']),
            'image_url'=>fake()->imageUrl(),
            'persentage'=>fake()->randomFloat(2,0.01,0.3),
            'telegram_url'=>fake()->url,
            'university_id'=>University::all()->random()->id,
        ];
    }
}
