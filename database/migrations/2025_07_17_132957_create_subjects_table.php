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
        Schema::create('seasons', function (Blueprint $table) {
            $table->id();
            $table->foreignId('year_id')->references('id')->on('years')->onDelete('cascade');
            $table->string('name');
            $table->integer('season_num');
            $table->boolean('is_deleted')->default(false);
            $table->timestamps();
        });
        Schema::create('subjects', function (Blueprint $table) {
            $table->id();
            $table->foreignId('season_id')->references('id')->on('seasons')->onDelete('cascade');;
            $table->string('name');
            $table->string('tagname');
            $table->enum('subject_nature',['writen','automation']);
            $table->boolean('is_deleted')->default(false);
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subjects');
    }
};
