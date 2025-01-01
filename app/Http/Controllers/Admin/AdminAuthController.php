<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminAuthController extends Controller
{
  // Show Admin Login page
  public function showLoginPage() {
    return view('admin.pages.login');
  }

  // Admin Login system
  public function login(Request $request) {

    // Data Validation
    $request -> validate([
      'auth'        => 'required',
      'password'    => 'required',
    ]);

    // Login attempt
    if ( Auth::guard('admin') -> attempt(['username' => $request -> auth, 'password' => $request -> password]) ||
         Auth::guard('admin') -> attempt(['email' => $request -> auth, 'password' => $request -> password]) ||
         Auth::guard('admin') -> attempt(['cell' => $request -> auth, 'password' => $request -> password])
    ) {
      if ( Auth::guard('admin') -> user() -> status && Auth::guard('admin') -> user() -> trash == false ) {
        return redirect() -> route('admin.dashboard');
      } else {
        Auth::guard('admin') -> logout(); // logged-out the user first, because user is already logged-in
        return redirect() -> route('admin.login.page') -> with('danger', 'This account is restricted');
      }
    } else {
      return redirect() -> route('admin.login.page') -> with('warning', 'Email/Password not matched');
    }

    return $request -> all();
  }

  // Admin Logout system
  public function logout() {
    // logout the user from Admin guard
    Auth::guard('admin') -> logout();
    // then redirect the user to admin login page
    return redirect() -> route('admin.login.page');
  }
}
