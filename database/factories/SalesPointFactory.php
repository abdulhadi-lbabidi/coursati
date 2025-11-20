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
            'phone'=>fake()->phoneNumber,
            'name'=>fake()->name,
            'address'=>fake()->address,
            'lat'=>fake()->randomFloat(10,0,16),
            'lng'=>fake()->randomFloat(10,0,16),
            'university_id'=>University::all()->random()->id,
        ];
    }
}
