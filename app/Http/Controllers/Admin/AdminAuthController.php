<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AdminAuthController extends Controller
{
  // Show Admin Login page
  public function showLoginPage() {
    return view('admin.pages.login');
  }
}
