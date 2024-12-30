<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AdminAuthController;
use App\Http\Controllers\Admin\AdminPageController;

Route::get('/', function () {
  return view('admin.pages.dashboard');
});

// Admin authentication routes
Route::get('/admin-login', [ AdminAuthController::class, 'showLoginPage' ]) -> name('admin.login.page');

// Admin page routes
Route::get('/admin-dashboard', [ AdminPageController::class, 'showDashboard' ]) -> name('admin.dashboard');
