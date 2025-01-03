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
    Schema::create('sliders', function (Blueprint $table) {
      $table->id();
      $table->string('hall');
      $table->string('room');
      $table->string('seat');
      $table->string('gender');
      $table->string('photo') -> nullable();
      $table->text('btns') -> nullable();
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
    Schema::dropIfExists('sliders');
  }
};