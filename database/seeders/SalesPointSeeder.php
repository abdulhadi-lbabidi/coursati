<?php

namespace Database\Seeders;

use App\Models\SalesPoint;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SalesPointSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        SalesPoint::factory()->create([
            'phone' => '+963944415236',
            'name' =>'مكتبة رقم 1',
            'address' => fake()->address,
            'description'=>fake()->text(),
            'lat'=>fake()->latitude,
            'lng'=>fake()->longitude,
            'university_id' => 1
        ]);
        SalesPoint::factory()->create([
            'phone' => '+963944415236',
            'name' =>'مكتبة رقم 2',
            'address' => fake()->address,
            'description'=>fake()->text(),
            'lat'=>fake()->latitude,
            'lng'=>fake()->longitude,
            'university_id' => 1
        ]);
        SalesPoint::factory()->create([
            'phone' => '+963944415236',
            'name' =>'مكتبة رقم 3',
            'address' => fake()->address,
            'description'=>fake()->text(),
            'lat'=>fake()->latitude,
            'lng'=>fake()->longitude,
            'university_id' => 1
        ]);
    }
}
