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

  // Admin login system
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
      return redirect() -> route('admin.dashboard');
    } else {
      return redirect() -> route('admin.login.page') -> with('warning', 'Email/Password not matched');
    }

    return $request -> all();
  }
}
