<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;

class Admin extends Authenticatable
{
  use Notifiable, SoftDeletes;

  protected $guard = 'admin';

  protected $guarded = [];  // Keep your existing mass assignment protection setting

  protected $hidden = [
    'password',
    'remember_token',
  ];

  protected $casts = [
    'status' => 'boolean',
    'semester_year' => 'integer',
  ];

  // Relationship with Role
  public function role() {
    return $this->belongsTo(Role::class);
  }

  // Add relationship with Seat if needed
  public function seats() {
    return $this->hasMany(Seat::class);
  }
}
