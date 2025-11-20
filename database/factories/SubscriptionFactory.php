<?php

namespace Database\Factories;

use App\Models\Course;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Subscription>
 */
class SubscriptionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'price'=>fake()->numberBetween(10,20),
            'enddate'=>fake()->date(),
            'cource_id'=>Course::all()->random()->id,
            'courese_type'=>fake()->randomElement(['telegram','youtube']),
            'is_pending'=>fake()->boolean(),
            'name'=>fake()->name(),
            'desc'=>fake()->text(),
            'tag_name'=>fake()->name(),
            'free_course_description'=>fake()->text(),
            'free_course_image'=>fake()->url(),
            'lectures_number'=>fake()->numberBetween(1,10),
            'total_videos_duration'=>fake()->numberBetween(1,20),
            'telegram_url'=>fake()->url(),
        ];
    }
}
