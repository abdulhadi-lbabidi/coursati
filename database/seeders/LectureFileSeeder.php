<?php

namespace Database\Seeders;

use App\Models\LectureFile;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class LectureFileSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        LectureFile::factory()->count(40)->create();
    }
}
