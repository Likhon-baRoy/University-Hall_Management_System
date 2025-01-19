<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SeatApplication extends Model
{
  use SoftDeletes;

  protected $guarded = [];

  protected $dates = [
    'processed_at',
  ];

  protected $casts = [
    'processed_at' => 'datetime',
  ];

  // Relationships
  public function user()
  {
    return $this->belongsTo(Admin::class, 'user_id');
  }

  public function currentSeat()
  {
    return $this->belongsTo(Seat::class, 'current_seat_id');
  }

  public function requestedSeat()
  {
    return $this->belongsTo(Seat::class, 'requested_seat_id');
  }

  public function processor()
  {
    return $this->belongsTo(Admin::class, 'processed_by');
  }

  // Scopes
  public function scopePending($query)
  {
    return $query->where('status', 'pending');
  }

  public function scopeProcessed($query)
  {
    return $query->whereIn('status', ['approved', 'rejected']);
  }

  // Check if application can be processed
  public function canBeProcessed()
  {
    if ($this->status !== 'pending') {
      return false;
    }

    if ($this->application_type === 'change') {
      return $this->requestedSeat && $this->requestedSeat->status;
    }

    return true;
  }
}
