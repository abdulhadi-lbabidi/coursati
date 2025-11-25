<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Contactinfo>
 */
class ContactinfoFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'phone'=>'0991666999',
            'email'=>'coursati@gmail.com',
            'insta_url'=>'https://www.google.com',
            'facebook_url'=>'https://www.google.com',
            'telegram_url'=>'https://www.google.com',
        ];
    }
}
