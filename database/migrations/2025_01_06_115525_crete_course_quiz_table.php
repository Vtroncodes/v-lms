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
        Schema::create('course_quiz', function (Blueprint $table) {
            $table->foreignId('course_id')->constrained('courses')->onDelete('cascade'); // Foreign key to courses
            $table->foreignId('quiz_id')->constrained('quizzes')->onDelete('cascade');
            $table->timestamps(); // Created at and updated at timestamps
            $table->integer('order')->nullable(); // Lesson order in the course
            $table->unique(['course_id', 'quiz_id']); // Optional: Prevent duplicate relationships
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
