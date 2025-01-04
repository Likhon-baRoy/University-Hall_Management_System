<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\HallController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\SlideController;
use App\Http\Controllers\FrontendPageController;
use App\Http\Controllers\Admin\AdminAuthController;
use App\Http\Controllers\Admin\AdminPageController;
use App\Http\Controllers\Admin\PermissionController;
use App\Http\Controllers\Admin\HallRoomController;
use App\Http\Controllers\Admin\HallSeatController;

Route::get('/', function () {
  return view('admin.pages.dashboard');
});

// Admin authentication routes
Route::group([ 'middleware' => 'admin.redirect'], function() {

  // Registration routes
  Route::post('/admin-register', [ AdminAuthController::class, 'register' ])->name('admin.register');
  Route::get('/admin-register', [ AdminAuthController::class, 'showRegisterPage' ]) -> name('admin.register.page');

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

  // Admin routes
  Route::resource('/admin-user', AdminController::class);
  Route::get('/admin-user-status-update/{id}', [ AdminController::class, 'updateStatus' ]) -> name('admin.status.update');
  Route::get('/admin-user-trash-update/{id}', [ AdminController::class, 'updateTrash' ]) -> name('admin.trash.update');
  Route::get('/admin-trash', [ AdminController::class, 'trashUsers' ]) -> name('admin.trash');

  // Slider routes
  Route::resource('/slider', SlideController::class);
  Route::get('/slider-status-update/{id}', [ SlideController::class, 'updateStatus'])->name('slider.status.update');
  Route::get('/slider-trash-update/{id}', [ SlideController::class, 'updateTrash'])->name('slider.trash.update');
  Route::get('/slider-trash', [ SlideController::class, 'trashSlider'])->name('slider.trash');

  // Hall routes
  Route::resource('/hall', HallController::class);
  Route::get('/hall-status-update/{id}', [ HallController::class, 'updateStatus'])->name('hall.status.update');
  Route::get('/hall-trash-update/{id}', [ HallController::class, 'updateTrash'])->name('hall.trash.update');
  Route::get('/hall-trash', [ HallController::class, 'trashHall'])->name('hall.trash');

  // Hall-Room routes
  Route::resource('/hall-room', HallRoomController::class);
  Route::get('/hall-room-status-update/{id}', [ HallRoomController::class, 'updateStatus'])->name('room.status.update');
  Route::get('/hall-room-trash-update/{id}', [ HallRoomController::class, 'updateTrash'])->name('room.trash.update');
  Route::get('/hall-room-trash', [ HallRoomController::class, 'trashHall'])->name('hall-room.trash');

  // Hall-Seat routes
  Route::resource('/hall-seat', HallSeatController::class);
  Route::get('/hall-seat-status-update/{id}', [ HallSeatController::class, 'updateStatus'])->name('seat.status.update');
  Route::get('/hall-seat-trash-update/{id}', [ HallSeatController::class, 'updateTrash'])->name('seat.trash.update');
  Route::get('/hall-seat-trash', [ HallSeatController::class, 'trashHall'])->name('hall-seat.trash');

  /*   Route::get('create/{room}', [ HallSeatController::class, 'create'])->name('hall-seat.create'); */

  // user profile routes
  Route::resource('/profile', ProfileController::class);
  /*   Route::get('/show-profile/{id}', [ ProfileController::class, 'showProfile' ]) -> name('show.profile'); */

  /**
   * Frontend routes
   */
  Route::get('/', [ FrontendPageController::class, 'showHomePage' ]) -> name('home.page');
  Route::get('/book', [ FrontendPageController::class, 'showBookPage' ]) -> name('book.page');
});
