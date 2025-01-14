<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Problem extends Model
{
  use SoftDeletes;

  protected $guarded = [];

  protected $casts = [
    'resolved_at' => 'datetime',
    'admin_responded_at' => 'datetime'
  ];

  // New scopes
  public function scopeActive($query)
  {
    return $query->whereNull('deleted_at');
  }

  public function scopeTrashed($query)
  {
    return $query->whereNotNull('deleted_at');
  }

  public function scopeResolved($query)
  {
    return $query->whereIn('status', ['resolved', 'closed']);
  }

  // Check if problem is old enough to archive (e.g., 30 days)
  public function shouldArchive()
  {
    if (!in_array($this->status, ['resolved', 'closed'])) {
      return false;
    }
    return $this->updated_at->addDays(30)->isPast();
  }

  // Relationships
  public function user() {
    return $this->belongsTo(Admin::class, 'user_id');
  }

  public function handledBy() {
    return $this->belongsTo(Admin::class, 'handled_by');
  }
}
