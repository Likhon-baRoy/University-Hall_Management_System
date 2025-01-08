<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Hall extends Model
{
  use SoftDeletes;

  protected $guarded = [];

  public function rooms()
  {
    return $this->hasMany(Room::class);
  }
}
