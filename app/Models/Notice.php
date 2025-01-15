<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Notice extends Model
{
  use HasFactory, SoftDeletes;

  protected $guarded = [];

  protected $casts = [
    'status' => 'boolean',
    'is_featured' => 'boolean',
    'published_at' => 'datetime',
    'expired_at' => 'datetime'
  ];

  public function creator()
  {
    return $this->belongsTo(Admin::class, 'created_by');
  }
}
