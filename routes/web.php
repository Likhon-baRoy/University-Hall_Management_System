<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AdminAuthController;
use App\Http\Controllers\Admin\AdminPageController;
use App\Http\Controllers\Admin\PermissionController;
use App\Http\Controllers\Admin\RoleController;

Route::get('/', function () {
  return view('admin.pages.dashboard');
});

// Admin authentication routes
Route::group([ 'middleware' => 'admin.redirect'], function() {
  Route::get('/admin-login', [ AdminAuthController::class, 'showLoginPage' ]) -> name('admin.login.page');
  Route::post('/admin-login', [ AdminAuthController::class, 'login' ]) -> name('admin.login');
});

// Admin page routes
Route::group([ 'middleware' => 'admin'], function() {
  Route::get('/admin-dashboard', [ AdminPageController::class, 'showDashboard' ]) -> name('admin.dashboard');
  Route::get('/admin-logout', [ AdminAuthController::class, 'logout' ]) -> name('admin.logout');

  // Admin Permission routes
  Route::resource('/permission', PermissionController::class);

  // Roles route
  Route::resource('/role', RoleController::class);
});
