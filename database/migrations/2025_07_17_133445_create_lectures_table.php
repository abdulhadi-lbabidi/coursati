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
        Schema::create('lectures', function (Blueprint $table) {
            $table->id();
            $table->foreignId('course_id')->references('id')->on('courses')->onDelete('cascade');;
            $table->string('name');
            $table->integer('number');
            $table->string('lecture_tag_name');
            $table->text('desc');
            $table->timestamps();
        });
        Schema::create('lecture_files', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lecture_id')->references('id')->on('lectures')->onDelete('cascade');
            $table->string('name');
            $table->float('size');
            $table->boolean('is_free')->default(false);
            $table->integer('number');
            $table->text('file_url');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lectures');
        Schema::dropIfExists('lecture_files');

    }
};
