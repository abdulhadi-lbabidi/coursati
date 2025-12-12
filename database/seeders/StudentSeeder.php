<?php

namespace Database\Seeders;

use App\Models\Student;
use App\Models\StudentCourse;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class StudentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Student::factory()->count(30)->create();
        StudentCourse::factory()->count(1200)->create();

    }
}
