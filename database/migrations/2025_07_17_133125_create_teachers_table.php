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
        Schema::create('teachers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('telegram_url')->nullable();
            $table->string('description')->nullable();
            $table->enum('statue',['deleted','banned','pending','active'])->default('pending');
            $table->enum('gender',['ذكر','أنثى']);
            $table->text('image_url')->nullable();
            $table->decimal('persentage')->default(0);
            $table->foreignId('university_id')->references('id')->on('universities')->onDelete('cascade');;
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('teachers');
    }
};
