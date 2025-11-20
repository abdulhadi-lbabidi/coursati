<?php

namespace Database\Factories;

use App\Models\Student;
use App\Models\Subscription;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\StudentSubscription>
 */
class StudentSubscriptionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'subscription_price'=>fake()->numberBetween(10,20),
            'student_id'=>Student::all()->random()->id,
            'subscription_id'=>Subscription::all()->random()->id,
        ];
    }
}
