<?php

namespace App\Http\Middleware\Admin;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class AdminAuthRedirectMiddleware
{
  /**
   * Handle an incoming request.
   *
   * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
   */
  public function handle(Request $request, Closure $next): Response
  {
    // check if user is already logged in then can't access Login page
    if (Auth::guard('admin')->check()) {
      return redirect()->route('admin.dashboard');
    }
    // else let user go to login page
    return $next($request);
  }
}
