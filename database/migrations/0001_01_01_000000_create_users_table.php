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
      $table->id();
      $table->unsignedInteger('role_id')->default(1);
      $table->string('name');
      $table->string('user_id')->unique();
      $table->string('username')->unique();
      $table->string('email')->unique();
      $table->string('cell')->unique();
      $table->string('password');
      $table->enum('role', ['student', 'teacher', 'staff', 'admin']);
      $table->enum('gender', ['male', 'female', 'other']);
      $table->string('department');
      $table->enum('semester_type', ['trimester', 'bi-semester']);
      $table->enum('semester', ['summer', 'fall', 'winter']);
      $table->year('semester_year');
      $table->string('hall_name');
      $table->string('room');
      $table->string('seat')->nullable();
      $table->string('dob')->nullable(); // Date-of-Birth
      $table->string('address')->nullable();
      $table->text('bio')->nullable();
      $table->string('photo')->default('avatar.png');
      $table->boolean('status')->default(false);
      $table->softdeletes();
      $table->timestamp('email_verified_at')->nullable();
      $table->rememberToken();
      $table->timestamps();
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
