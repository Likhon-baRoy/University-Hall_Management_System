<?php

namespace App\Http\Middleware\Admin;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class AdminAuthMiddleware
{
  /**
   * Handle an incoming request.
   *
   * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
   */
  public function handle(Request $request, Closure $next): Response
  {

    // if user is logged-in then let user go to request
    if (Auth::guard('admin')->check()) {
      return $next($request);
    }
    // else redirect user to login page
    return redirect()->route('admin.login.page');
  }
}
