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
        Schema::create('lesson_topic', function (Blueprint $table) {
            $table->id(); // Primary key
            $table->foreignId('lesson_id')->constrained()->onDelete('cascade'); // Foreign key to courses
            $table->foreignId('topic_id')->constrained()->onDelete('cascade'); // Foreign key to lessons
            $table->integer('order')->nullable(); // Lesson order in the course
            $table->timestamps(); // Created at and updated at timestamps
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lesson_topic');
    }
};
