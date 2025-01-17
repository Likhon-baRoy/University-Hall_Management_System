<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmailOtp extends Model
{
  protected $fillable = ['email', 'otp', 'verified', 'expires_at'];

  protected $casts = [
    'verified' => 'boolean',
    'expires_at' => 'datetime',
  ];

  public function isValid()
  {
    return !$this->verified && now()->lt($this->expires_at);
  }
}
