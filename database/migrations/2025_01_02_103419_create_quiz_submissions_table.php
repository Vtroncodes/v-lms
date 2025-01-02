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
        Schema::create('quiz_submissions', function (Blueprint $table) {
            $table->id(); // Primary key
            $table->unsignedBigInteger('quizable_id'); // Foreign key referencing quizables
            $table->unsignedBigInteger('student_id'); // Foreign key referencing users (students)
            $table->integer('score')->nullable(); // Total score for the quiz
            $table->timestamp('submitted_at')->nullable(); // Timestamp for when the quiz was submitted
            $table->timestamps(); // Created at and updated at timestamps

            // Foreign key constraints
            $table->foreign('quizable_id')->references('id')->on('quizables')->onDelete('cascade');
            $table->foreign('student_id')->references('id')->on('users')->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quiz_submissions');
    }
};
