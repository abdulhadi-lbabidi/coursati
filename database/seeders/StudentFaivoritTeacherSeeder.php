<?php

namespace Database\Seeders;

use App\Models\StudentFaivoritTeacher;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class StudentFaivoritTeacherSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        StudentFaivoritTeacher::factory()->count(100)->create();
    }
}
