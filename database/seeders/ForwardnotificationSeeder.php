<?php

namespace Database\Seeders;

use App\Models\Forwardnotification;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ForwardnotificationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Forwardnotification::factory()->count(30)->create();
    }
}
