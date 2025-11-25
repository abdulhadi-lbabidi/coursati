<?php

namespace Database\Factories;

use App\Models\Lecture;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Video>
 */
class VideoFactory extends Factory
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
            'desc'=>fake()->text,
            'video_url'=>fake()->url,
            'views'=>fake()->numberBetween(10,5000),
            'is_free'=>fake()->boolean,
            'video_tag_name'=>fake()->name,
            'number'=>fake()->randomNumber(1,10),
            'lecture_id'=>Lecture::all()->random()->id,
        ];
    }
}
