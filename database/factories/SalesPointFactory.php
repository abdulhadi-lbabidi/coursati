<?php

namespace Database\Factories;

use App\Models\University;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\SalesPoint>
 */
class SalesPointFactory extends Factory
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
            'phone'=>fake()->phoneNumber,
            'address'=>fake()->address,
            'description'=>fake()->text(),
            'lat'=>fake()->randomFloat(10,0,16),
            'lng'=>fake()->randomFloat(10,0,16),
            'image_url'=>fake()->imageUrl(),
            'university_id'=>University::all()->random()->id,
        ];
    }
}
