<?php
// app/Http/Middleware/RedirectIfAuthenticated.php
namespace App\Http\Middleware\Admin;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RedirectIfAuthenticated
{
  public function handle(Request $request, Closure $next, ...$guards)
  {
    $guards = empty($guards) ? [null] : $guards;

    foreach ($guards as $guard) {
      if (Auth::guard($guard)->check()) {
        if ($guard === 'admin') {
          return redirect()->route('admin.dashboard');
        }
        return redirect()->route('user.dashboard');
      }
    }

    return $next($request);
  }
}
