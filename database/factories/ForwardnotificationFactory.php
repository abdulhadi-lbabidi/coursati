<?php

namespace Database\Factories;

use App\Models\Student;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Forwardnotification>
 */
class ForwardnotificationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title'=>fake()->name,
            'description'=>fake()->text(),
            'notifi_id'=>Student::all()->random()->id,
            'notifi_type'=>'students',
        ];
    }
}
