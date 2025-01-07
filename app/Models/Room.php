<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
  protected $guarded = [];

  public function hall()
  {
    return $this->belongsTo(Hall::class);
  }

  public function seats()
  {
    return $this->hasMany(Seat::class);
  }
}
