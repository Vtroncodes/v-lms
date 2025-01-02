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
        Schema::create('ratings_reviews', function (Blueprint $table) {
            $table->id(); // Primary key
            $table->unsignedBigInteger('course_id'); // Foreign key referencing courses
            $table->unsignedBigInteger('student_id'); // Foreign key referencing users
            $table->tinyInteger('rating'); // Rating value (1-5 or 1-10 scale)
            $table->longtext('review')->nullable(); // Review text provided by the student (nullable)
            $table->timestamps(); // Created at and updated at timestamps

            // Foreign key constraints
            $table->foreign('course_id')->references('id')->on('courses')->onDelete('cascade');
            $table->foreign('student_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rating_reviews');
    }
};
