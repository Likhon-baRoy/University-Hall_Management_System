<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Auth\Passwords\CanResetPassword;

class Admin extends Authenticatable
{
  use Notifiable, SoftDeletes, CanResetPassword;

  protected $guard = 'admin';

  protected $guarded = [];  // Keep your existing mass assignment protection setting

  protected $hidden = [
    'password',
    'remember_token',
  ];

  protected $casts = [
    'status' => 'boolean',
    'semester_year' => 'integer',
    'posts_count_reset_at' => 'datetime'
  ];

  // Relationship with Role
  public function role() {
    return $this->belongsTo(Role::class);
  }

  // Add relationship with Seat if needed
  public function seats() {
    return $this->hasMany(Seat::class);
  }

  // automatically set the role based on user_type
  protected static function booted()
  {
    static::creating(function ($admin) {
      // If no role_id is set but user_type is present
      if (!$admin->role_id && $admin->user_type) {
        // Find the role with matching slug
        $role = Role::where('slug', $admin->user_type)->first();
        if ($role) {
          $admin->role_id = $role->id;
        }
      }
    });
  }

  public function reports() {
    return $this->hasMany(Report::class);
  }
}
