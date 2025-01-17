<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
  public function up(): void
  {
    Schema::create('admins', function (Blueprint $table) {
      $table->id();
      $table->foreignId('role_id')->nullable()->constrained('roles')->onDelete('set null');
      $table->string('name');
      $table->string('user_id')->unique();
      $table->string('username')->unique();
      $table->string('email')->unique();
      $table->string('cell')->unique();
      $table->string('password');
      $table->enum('user_type', ['student', 'teacher', 'staff', 'admin', 'sadmin', 'editor', 'author']);
      $table->enum('gender', ['male', 'female', 'other']);
      $table->string('dept')->nullable(); // department
      $table->enum('semester_type', ['trimester', 'bi-semester'])->nullable();
      $table->enum('semester', ['summer', 'fall', 'winter'])->nullable();
      $table->year('semester_year')->nullable();
      $table->string('hall')->nullable();
      $table->string('room')->nullable();
      $table->string('seat')->nullable();
      $table->string('dob')->nullable(); // Data-of_Birth
      $table->string('address')->nullable();
      $table->text('bio')->nullable();
      $table->string('photo')->default('avatar.png');
      $table->boolean('status')->default(false);
      $table->integer('daily_problem_posts')->default(0); // Added to give limit user in problem posting
      $table->timestamp('posts_count_reset_at')->nullable();
      $table->rememberToken();  // Added for authentication
      $table->softDeletes();
      $table->timestamps();
    });
  }

  public function down(): void
  {
    Schema::dropIfExists('admins');
  }
};
