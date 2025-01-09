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
    Schema::create('rooms', function (Blueprint $table) {
      $table->id();
      $table->foreignId('hall_id')->constrained('halls')->onDelete('cascade')->onUpdate('cascade');
      $table->string('name');
      $table->string('photo')->nullable();
      $table->boolean('status')->default(true);
      $table->softDeletes();
      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('rooms');
  }
};
