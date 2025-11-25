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
        Schema::create('videos', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('desc');
            $table->text('video_url');
            $table->unsignedBigInteger('views');
            $table->boolean('is_free')->default(false);
            $table->string('video_tag_name');
            $table->integer('number');
            $table->foreignId('lecture_id')->references('id')->on('lectures')->onDelete('cascade');
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('videos');

    }
};
