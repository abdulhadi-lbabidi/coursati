<?php

use App\Models\University;
use App\Models\Year;
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
            $table->string('name');
            $table->integer('number');
            $table->foreignIdFor(University::class);
            $table->boolean('is_deleted')->default(false);
            $table->timestamps();
        });
        Schema::create('subjects', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('doctor_name');
            $table->string('subject_tag_name');
            $table->enum('subject_nature',['writen','automation']);
            $table->boolean('is_deleted')->default(false);
            $table->foreignIdFor(Year::class);
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
