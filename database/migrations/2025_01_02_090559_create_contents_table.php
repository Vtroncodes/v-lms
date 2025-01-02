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
        Schema::create('content', function (Blueprint $table) {
            $table->id(); // Primary key
            $table->unsignedBigInteger('contentable_id'); // Polymorphic ID
            $table->string('contentable_type'); // Polymorphic Type
            $table->enum('type', ['text', 'video', 'image', 'iframe', 'file']); // Content type
            $table->longText('content')->nullable(); // Text or iframe content
            $table->string('media_url')->nullable(); // Media file URL for videos/images
            $table->string('file_url')->nullable(); // URL for downloadable files
            $table->string('file_type')->nullable(); // Type of file (e.g., pdf, docx)
            $table->integer('order')->default(0); // Content display order
            $table->timestamps(); // Created at and updated at timestamps
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contents');
    }
};
