<?php

namespace Database\Seeders;

use App\Models\SubjectTeachers;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SubjectTeachersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        SubjectTeachers::factory()->count(200)->create();
    }
}
