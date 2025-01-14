<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
  public function up(): void
  {
    Schema::create('problems', function (Blueprint $table) {
      $table->id();
      $table->foreignId('user_id')->constrained('admins')->onDelete('cascade'); // The user reporting the problem
      $table->string('title');
      $table->text('description');
      $table->enum('issue_type', ['hall', 'room', 'seat', 'other']);
      $table->string('location');
      $table->enum('priority', ['low', 'medium', 'high', 'urgent']);
      $table->enum('status', ['pending', 'in_progress', 'resolved', 'closed'])->default('pending');
      $table->text('admin_response')->nullable();
      $table->foreignId('handled_by')->nullable()->constrained('admins')->onDelete('set null'); // Admin handling the case
      $table->timestamp('admin_responded_at')->nullable();
      $table->timestamps();
      $table->softDeletes();
    });
  }

  public function down(): void
  {
    Schema::dropIfExists('problems');
  }
};
