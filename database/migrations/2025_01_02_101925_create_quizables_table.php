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
        Schema::create('quizables', function (Blueprint $table) {
            $table->id(); // Primary key
            $table->unsignedBigInteger('quiz_id'); // Foreign key referencing quizzes
            $table->unsignedBigInteger('quizable_id'); // The ID of the related entity
            $table->string('quizable_type'); // The type of the related entity (e.g., Course, Topic, Lesson)

            // Foreign key constraints
            $table->foreign('quiz_id')->references('id')->on('quizzes')->onDelete('cascade');

            $table->timestamps(); // Created at and updated at timestamps
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quizables');
    }
};
