<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
  public function up()
  {
    Schema::table('seats', function (Blueprint $table) {
      $table->foreignId('admin_id')->nullable()->after('status')
            ->constrained('admins')
            ->onDelete('set null');
    });
  }

  public function down()
  {
    Schema::table('seats', function (Blueprint $table) {
      $table->dropForeign(['admin_id']);
      $table->dropColumn('admin_id');
    });
  }
};
