<?php

namespace Database\Factories;

use App\Models\Video;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\VideoTiming>
 */
class VideoTimingFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'video_id'=>Video::all()->random()->id,
            'minutes'=>fake()->numberBetween(0,59),
            'secounds'=>fake()->numberBetween(0,59),
            'name'=>fake()->name(),
        ];
    }
}
