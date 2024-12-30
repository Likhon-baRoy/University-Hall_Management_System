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
    Schema::create('admins', function (Blueprint $table) {
      $table->id();
      $table->unsignedInteger('role_id') -> default(3);
      $table->string('name');
      $table->string('email') -> unique();
      $table->string('cell') -> unique();
      $table->string('username') -> unique();
      $table->string('password');
      $table->string('dob') -> nullable(); // Date-of-Birth
      $table->string('address') -> nullable();
      $table->text('bio') -> nullable();
      $table->string('photo') -> default('avatar.png');
      $table->string('access_token') -> nullable();
      $table->boolean('status') -> default(true);
      $table->boolean('trash') -> default(false);
      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('admins');
  }
};
