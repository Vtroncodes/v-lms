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
        Schema::create('questions', function (Blueprint $table) {
            $table->id(); // Primary key
            $table->longtext('question'); // The question text
            $table->json('options'); // Options for the question (stored as JSON)
            $table->integer('correct_option'); // Index of the correct option
            $table->unsignedBigInteger('quiz_id'); // Foreign key referencing quizzes
            $table->timestamps(); // Created at and updated at timestamps
            $table->integer('question_order')->nullable(); // question order in the course
            // Foreign key constraint
             $table->foreign('quiz_id')->references('id')->on('quizzes')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('questions');
    }
};
