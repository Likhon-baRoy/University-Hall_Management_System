<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
  public function up(): void
  {
    Schema::create('seat_applications', function (Blueprint $table) {
      $table->id();
      $table->foreignId('user_id')->constrained('admins')->onDelete('cascade');
      $table->foreignId('current_seat_id')->nullable()->constrained('seats')->onDelete('set null');
      $table->foreignId('requested_seat_id')->nullable()->constrained('seats')->onDelete('set null');
      $table->enum('application_type', ['change', 'cancel']);
      $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
      $table->text('reason');
      $table->text('admin_note')->nullable();
      $table->timestamp('processed_at')->nullable();
      $table->foreignId('processed_by')->nullable()->constrained('admins')->onDelete('set null');
      $table->timestamps();
      $table->softDeletes();
    });
  }

  public function down(): void
  {
    Schema::dropIfExists('seat_applications');
  }
};
