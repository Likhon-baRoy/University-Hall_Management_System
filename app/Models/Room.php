<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Room extends Model
{
  use SoftDeletes;

  protected $guarded = [];

  /**
   * Scope to get only active rooms from active halls
   */
  public function scopeActive($query)
  {
    return $query->where('status', true)
                 ->whereNull('deleted_at')
                 ->whereHas('hall', function($query) {
                   $query->where('status', true)
                         ->whereNull('deleted_at');
                 });
  }

  public function hall()
  {
    return $this->belongsTo(Hall::class);
  }

  public function seats()
  {
    return $this->hasMany(Seat::class);
  }
}
