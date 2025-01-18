<?php

// app/Http/Middleware/Authenticate.php
namespace App\Http\Middleware\Admin;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;

class Authenticate extends Middleware
{
  protected function redirectTo(Request $request): ?string
  {
    if (!$request->expectsJson()) {
      // If trying to access admin routes
      if ($request->is('admin/*')) {
        return route('admin.login.page');
      }
      // Store room ID if coming from booking
      if ($request->has('room')) {
        session(['intended_room_booking' => $request->room]);
      }
      return route('login');
    }
    return null;
  }
}
