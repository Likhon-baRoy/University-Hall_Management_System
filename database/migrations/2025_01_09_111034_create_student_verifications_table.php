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
    Schema::create('student_verifications', function (Blueprint $table) {
      $table->id();
      $table->string('user_id')->unique();
      $table->string('email')->unique();
      $table->string('department');
      $table->enum('gender', ['male', 'female', 'other']);
      $table->boolean('is_registered')->default(false);
      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('student_verifications');
  }
};
