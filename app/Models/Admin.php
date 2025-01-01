<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User;
use Illuminate\Notifications\Notifiable;

class Admin extends User
{
  use Notifiable;

  protected $guarded = []; // empty array mean all column is accessable

  // get user role
  public function role() {
    return $this -> belongsTo(Role::Class, 'role_id', 'id');
  }
}
