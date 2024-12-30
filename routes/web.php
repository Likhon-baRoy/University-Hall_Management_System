<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AdminAuthController;
use App\Http\Controllers\Admin\AdminPageController;

Route::get('/', function () {
  return view('admin.pages.dashboard');
});

// Admin authentication routes
Route::group([ 'middlleware' => 'admin.redirect'], function() {
  Route::get('/admin-login', [ AdminAuthController::class, 'showLoginPage' ]) -> name('admin.login.page');
  Route::post('/admin-login', [ AdminAuthController::class, 'login' ]) -> name('admin.login');
});

// Admin page routes
Route::group([ 'middlleware' => 'admin'], function() {
  Route::get('/admin-dashboard', [ AdminPageController::class, 'showDashboard' ]) -> name('admin.dashboard');
});
