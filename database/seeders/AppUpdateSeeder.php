<?php

namespace Database\Seeders;

use App\Models\AppUpdate;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AppUpdateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        AppUpdate::factory()->create([
            'publish_number'=> '0.0.1',
            'publish_date' => '2025-7-9',
            'desc' => 'إصلاح بعض الأخطاء',
        ]);
        AppUpdate::factory()->create([
            'publish_number'=> '0.0.2',
            'publish_date' => '2025-7-15',
            'desc' => 'إصلاح بعض الأخطاء',
        ]);
    }
}
