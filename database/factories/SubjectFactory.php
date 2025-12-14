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
        // Pick a random year
        $year = Year::inRandomOrder()->first();

        if (!$year) {
            // Handle no year found, maybe throw or create one
            throw new \Exception('No Year found in database.');
        }

        // Find a season with the same university_id as the year
        $season = Season::where('university_id', $year->university_id)->inRandomOrder()->first();

        if (!$season) {
            // Handle no season found for that university
            throw new \Exception('No Season found for the university of the selected Year.');
        }
        return [
            'name'=>fake()->name,
            'doctor_name'=>fake()->name,
            'subject_tag_name'=>fake()->name,
            'subject_nature'=>fake()->randomElement(['writen','automation']),
            'is_deleted'=>fake()->boolean(10),
            'year_id'=>Year::all()->random()->id,
            'season_id'=>Season::all()->random()->id,
        ];
    }
}
