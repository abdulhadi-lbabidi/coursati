<?php

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
        Schema::create('courses', function (Blueprint $table) {
            $table->id();
            $table->boolean('is_free')->default(false);
            $table->boolean('is_deleted')->default(false);
            $table->foreignId('teacher_id')->references('id')->on('teachers')->onDelete('cascade');
            $table->foreignId('subject_id')->references('id')->on('subjects')->onDelete('cascade');
            $table->timestamps();
        });
        Schema::create('subscriptions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cource_id')->references('id')->on('courses')->onDelete('cascade');
            $table->enum('courese_type',['youtube','telegram']);
            $table->integer('is_pending');
            $table->string('name');
            $table->text('desc');
            $table->string('tag_name');
            $table->text('free_course_description');
            $table->text('free_course_image');
            $table->integer('lectures_number');
            $table->float('total_videos_duration');
            $table->text('telegram_url');
            $table->float('price');
            $table->date('enddate');
            $table->timestamps();
        });
        Schema::create('student_subscriptions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('subscription_id')->references('id')->on('subscriptions')->onDelete('cascade');
            $table->foreignId('student_id')->references('id')->on('students')->onDelete('cascade');
            $table->integer('subscription_price');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('courses');
        Schema::dropIfExists('subscriptions');
        Schema::dropIfExists('student_subscriptions');
    }
};
