<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Role extends Model {
  // give permission to all column available in the Role table
  protected $guarded = [];

  // get role users
  public function users() {
    return $this -> hasMany(Admin::class, 'role_id', 'id');
  }
}
