<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Room extends Model
{
  use SoftDeletes;

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
