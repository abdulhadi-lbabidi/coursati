<?php

use App\Models\Course;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('teacher_notifications', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('description');
            $table->boolean('is_allowed')->default(false);
            $table->foreignId('university_id')->references('id')->on('universities')->onDelete('cascade');
            $table->foreignId('teacher_id')->references('id')->on('teachers');
            $table->foreignIdFor(Course::class)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('teacher_notifications');
    }
};
