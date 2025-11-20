<?php

namespace Database\Seeders;

use App\Models\VideoTiming;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class VideoTimingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        VideoTiming::factory()->count(30)->create();
    }
}
