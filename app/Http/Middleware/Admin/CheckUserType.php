<?php

namespace App\Http\Middleware\Admin;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckUserType
{
  public function handle(Request $request, Closure $next, ...$types)
  {
    $user = Auth::guard('admin')->user();

    if (!$user || !in_array($user->user_type, $types)) {
      return redirect()->route('admin.dashboard')
                       ->with('error', 'You do not have permission to access this feature.');
    }

    return $next($request);
  }
}
