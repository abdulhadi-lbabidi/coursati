<?php

namespace Database\Seeders;

use App\Models\Admin;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Student::factory()->create()->user()->create([
            'email' => 'student@coursaty.com',
            'password' => bcrypt('123.321A'),
            'email_verified_at' => time(),
            'phone' => '0911111111'
        ]);
        Teacher::factory()->create()->user()->create([
            'email' => 'teacher@coursaty.com',
            'password' => bcrypt('123.321A'),
            'email_verified_at' => time(),
            'phone' => '0911111112',
        ]);
        Admin::factory()->create()->user()->create([
            'email' => 'admin@coursaty.com',
            'password' => bcrypt('123.321A'),
            'email_verified_at' => time(),
            'phone' => '09111111113'
        ]);
    }
}
