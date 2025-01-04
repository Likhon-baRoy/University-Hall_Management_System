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
    Schema::create('seats', function (Blueprint $table) {
      $table->id();
      $table->foreignId('room_id')->constrained('rooms')->onDelete('cascade')->onUpdate('cascade');
      $table->string('name');
      $table->boolean('status')->default(true)->comment('ture=available, false=booked');
      $table->softdeletes();
      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('seats');
  }
};
