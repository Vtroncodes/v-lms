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
        Schema::create('users', function (Blueprint $table) {
            $table->id(); // Primary key
            $table->string('name'); // Name column
            $table->string('email')->unique(); // Unique email column
            $table->string('password'); // Password column
            $table->string('mobile_no'); // Mobile number column
            $table->text('profile_image')->nullable(); // Profile image (nullable)
            $table->timestamp('email_verified_at')->nullable();
            $table->string('email_verification_token', 255)->nullable(); // Email verification token
            $table->enum('role', ['Student', 'Instructor', 'Admin']); // Role column/
            $table->enum('membership_status', ['active', 'inactive', 'locked']); // Role column
            $table->dateTime('membership_start_date')->nullable(); // Membership start date
            $table->dateTime('membership_end_date')->nullable(); // Membership end date
            $table->timestamps(); // Created at and updated at timestamps
            
        }); 

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};
