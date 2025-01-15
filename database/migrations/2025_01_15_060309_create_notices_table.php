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
    Schema::create('notices', function (Blueprint $table) {
      $table->id();
      $table->string('title');
      $table->text('description')->nullable();
      $table->string('file_path');
      $table->string('file_name')->nullable(); // Original file name
      $table->string('file_size')->nullable(); // File size in bytes
      $table->enum('file_type', ['pdf', 'doc', 'docx'])->default('pdf');
      $table->boolean('status')->default(true); // For active/inactive state
      $table->boolean('is_featured')->default(false); // To highlight important notices
      $table->integer('view_count')->default(0); // Track how many times notice was viewed
      $table->foreignId('created_by')
            ->constrained('admins')
            ->onDelete('cascade');
      $table->timestamp('published_at')->nullable();
      $table->timestamp('expired_at')->nullable(); // Optional expiry date
      $table->timestamps();
      $table->softDeletes(); // For trash functionality
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('notices');
  }
};
