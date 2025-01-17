<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
  public function up()
  {
    Schema::create('email_otps', function (Blueprint $table) {
      $table->id();
      $table->string('email');
      $table->string('otp');
      $table->boolean('verified')->default(false);
      $table->timestamp('expires_at');
      $table->timestamps();
    });
  }

  public function down()
  {
    Schema::dropIfExists('email_otps');
  }
};
