<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\StudentCourse;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CourseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Course::factory()->count(200)->create();
        Course::factory()->count(150)->create(['price'=>0]);
    }
}
