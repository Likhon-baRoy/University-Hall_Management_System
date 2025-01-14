<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Role extends Model {
  // give permission to all column available in the Role table
  protected $guarded = [];

  // Cast permissions as an array
  protected $casts = [
    'permissions' => 'array',  // Automatically casts JSON to an array
  ];

  public function admins() {
    return $this->hasMany(Admin::class);
  }
}
