<?php

namespace App\Providers;

use App\Models\Admin;
use App\Models\Student;
use App\Models\Teacher;
use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Eloquent\Relations\Relation;
class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Relation::morphMap([
            'teachers' => Teacher::class,
            'students' => Student::class,
            'admin' => Admin::class,
        ]);

    }
}
