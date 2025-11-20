<?php

namespace Database\Seeders;

use App\Models\TeacherNotification;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TeacherNotificationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        TeacherNotification::factory()->count(50)->create();
    }
}
