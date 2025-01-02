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
        Schema::create('assignments', function (Blueprint $table) {
            $table->id(); // Primary key
            $table->string('title'); // Assignment title
            $table->text('description')->nullable(); // Assignment description
            $table->integer('duration'); // Duration in minutes or any relevant unit
            $table->unsignedBigInteger('assignmentable_id'); // Polymorphic ID
            $table->string('assignmentable_type'); // Polymorphic Type (Course, Lesson, Topic)
            $table->timestamps(); // Created at and updated at timestamps
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('assignments');
    }
};
