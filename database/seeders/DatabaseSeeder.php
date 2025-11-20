<?php

namespace Database\Seeders;


use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        // User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        $this->call([
            UniversitySeeder::class,]);
        $this->call([
            YearSeeder::class,]);
        $this->call([
            SalesPointSeeder::class,]);
        $this->call([
            SubjectSeeder::class,]);
        $this->call([
            TeacherSeeder::class,]);
        $this->call([
            CourseSeeder::class,]);
        $this->call([
            StudentSeeder::class,]);
        $this->call([
            SubscriptionSeeder::class,]);
        $this->call([
            LectureSeeder::class,]);
        $this->call([
            LectureFileSeeder::class,]);
        $this->call([
            VideoSeeder::class,]);
        $this->call([
            VideoTimingSeeder::class,]);
        $this->call([
            UserSeeder::class,]);
        $this->call([
            TeacherNotificationSeeder::class,]);
        $this->call([
            AppUpdateSeeder::class,]);
        $this->call([
            StudentSubjectsSeeder::class,]);
        // $this->call([
        //     NotificationSeeder::class,]);

    }


}
