<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('courses', function (Blueprint $table) {
            $table->id(); // Primary key
            $table->string('title'); // Course title
            $table->longText('description')->nullable(); // Course description (optional)
            $table->enum('level', ['Beginner', 'Intermediate', 'Advanced']); // Course level
            $table->string('duration'); // Duration of the course
            $table->enum('course_type', ['live', 'recorded', 'hybrid']); // Type of course
            $table->integer('allowed_retakes')->nullable(); // Allowed retakes (optional)
            $table->json('required_prerequisites_course_id')->nullable(); // Prerequisites in JSON format
            $table->string('certificate_url')->nullable(); // Certificate URL (optional)
            $table->string('directory_path')->nullable(); // Directory path for the course
            $table->timestamps(); // Created at and updated at timestamps
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('courses');
    }
};
