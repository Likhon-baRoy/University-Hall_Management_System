<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User;

class Admin extends User
{
  protected $guarded = []; // empty array mean all column is accessable
}
