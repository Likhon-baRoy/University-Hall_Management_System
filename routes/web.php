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
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Auth\OtpController;
use App\Http\Controllers\HallBookingController;
use App\Http\Controllers\ProblemController;
use App\Http\Controllers\SeatApplicationController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\NoticeController as FrontendNoticeController;
use App\Http\Controllers\Admin\NoticeController as AdminNoticeController;

// User Authentication Routes
Route::middleware(['guest'])->group(function () {
  Route::get('/login', [AuthController::class, 'showLoginPage'])->name('login');
  Route::post('/login', [AuthController::class, 'login'])->name('login.submit');
  Route::get('/register/{room?}', [AuthController::class, 'showRegisterPage'])->name('auth.register');
  Route::post('/register', [AuthController::class, 'register'])->name('register.submit');

  // Add these routes in web.php inside the guest middleware group
  Route::post('/send-otp', [OtpController::class, 'sendOtp']);
  Route::post('/verify-otp', [OtpController::class, 'verifyOtp']);

  // Password Reset Routes
  Route::get('/forgot-password', [AuthController::class, 'showForgotPasswordForm'])->name('password.request');
  Route::post('/forgot-password', [AuthController::class, 'sendResetLink'])->name('password.email');
  Route::get('/reset-password/{token}', [AuthController::class, 'showResetForm'])->name('password.reset');
  Route::post('/reset-password', [AuthController::class, 'resetPassword'])->name('password.update');
});

// User Protected Routes
Route::middleware(['auth'])->group(function () {
  /*   Route::get('/dashboard', [UserDashboardController::class, 'index'])->name('user.dashboard'); */
  Route::get('/admin-dashboard', [AdminPageController::class, 'showDashboard'])->name('admin.dashboard');
  Route::get('/logout', [AuthController::class, 'logout'])->name('logout');
});


// Admin authentication routes
Route::group(['middleware' => 'admin.redirect'], function () {
  Route::get('/admin-login', [AdminAuthController::class, 'showLoginPage'])->name('admin.login.page');
  Route::post('/admin-login', [AdminAuthController::class, 'login'])->name('admin.login');
});

// Admin page routes
Route::group(['middleware' => 'admin'], function () {

  /**
   * Admin panel header notifications
   */
  // Add these notification routes inside the admin group
  Route::controller(NotificationController::class)->group(function() {
    Route::get('/notifications', 'index')->name('notifications.index');
    Route::post('/notifications/{id}/mark-as-read', 'markAsRead')->name('notifications.mark-read');
    Route::post('/notifications/mark-all-read', 'markAllAsRead')->name('notifications.mark-all-read');
    Route::get('/notifications/count', 'getUnreadCount')->name('notifications.count');
  });

  Route::get('/admin-dashboard', [AdminPageController::class, 'showDashboard'])->name('admin.dashboard');
  Route::get('/admin-logout', [AdminAuthController::class, 'logout'])->name('admin.logout');

  // Admin Permission routes
  Route::resource('/permission', PermissionController::class);

  // Roles route
  Route::resource('/role', RoleController::class);

  // Admin routes
  // Admin-specific route should come first
  Route::get('admin-user/admin', [AdminController::class, 'adminIndex'])->name('admin-user.admin');
  // Then the resource route
  Route::resource('/admin-user', AdminController::class);
  // Route for viewing admin users only
  Route::get('admin-user/admin', [AdminController::class, 'adminIndex'])->name('admin-user.admin');
  Route::get('/admin-user-status-update/{id}', [AdminController::class, 'updateStatus'])->name('admin.status.update');
  Route::get('user/trash', [AdminController::class, 'trash'])->name('admin-user.trash');
  Route::post('user/{id}/restore', [AdminController::class, 'restore'])->name('admin-user.restore');
  Route::delete('user/{id}/force-delete', [AdminController::class, 'forceDelete'])->name('admin-user.force-delete');

  // Slider routes
  Route::resource('/slider', SlideController::class);
  Route::get('/slider-status-update/{id}', [SlideController::class, 'updateStatus'])->name('slider.status.update');
  Route::get('/slider-trash-update/{id}', [SlideController::class, 'updateTrash'])->name('slider.trash.update');
  Route::get('/slider-trash', [SlideController::class, 'trashSlider'])->name('slider.trash');

  // Hall routes
  Route::resource('/hall', HallController::class);
  Route::get('/hall-status-update/{id}', [HallController::class, 'updateStatus'])->name('hall.status.update');
  Route::get('halls/trash', [HallController::class, 'trash'])->name('hall.trash');
  Route::post('hall/{id}/restore', [HallController::class, 'restore'])->name('hall.restore');
  Route::delete('hall/{id}/force-delete', [HallController::class, 'forceDelete'])->name('hall.force-delete');

  // Hall-Room routes
  Route::resource('/hall-room', HallRoomController::class);
  Route::get('/hall-room-status-update/{id}', [HallRoomController::class, 'updateStatus'])->name('room.status.update');
  Route::get('rooms/trash', [HallRoomController::class, 'trash'])->name('hall-room.trash');
  Route::post('rooms/{id}/restore', [HallRoomController::class, 'restore'])->name('hall-room.restore');
  Route::delete('rooms/{id}/force-delete', [HallRoomController::class, 'forceDelete'])->name('hall-room.force-delete');

  // Hall-Seat routes
  Route::resource('/hall-seat', HallSeatController::class);
  Route::get('/hall-seat-status-update/{id}', [HallSeatController::class, 'updateStatus'])->name('seat.status.update');
  Route::get('/hall-seat-trash-update/{id}', [HallSeatController::class, 'updateTrash'])->name('seat.trash.update');
  Route::get('/hall-seat-trash', [HallSeatController::class, 'trashHall'])->name('hall-seat.trash');

  /*   Route::get('create/{room}', [ HallSeatController::class, 'create'])->name('hall-seat.create'); */

  // user profile routes
  Route::resource('/profile', ProfileController::class);
  Route::post('/profile/update-password', [ProfileController::class, 'updatePassword'])->name('profile.update-password');
  Route::put('/profile/update-photo', [ProfileController::class, 'updatePhoto'])->name('profile.update-photo');
  /*   Route::get('/show-profile/{id}', [ ProfileController::class, 'showProfile' ]) -> name('show.profile'); */

  // Add these routes with your other admin routes inside admin middleware group
  Route::group(['middleware' => 'admin', 'prefix' => 'admin', 'as' => 'admin.'], function () {
    // Notice Management Routes
    Route::controller(AdminNoticeController::class)->prefix('notices')->name('notices.')->group(function() {
      Route::get('/', 'index')->name('index');
      Route::get('/create', 'create')->name('create');
      Route::post('/', 'store')->name('store');
      Route::post('/{notice}/toggle-status', 'toggleStatus')->name('toggle-status');
      Route::get('/{notice}/edit', 'edit')->name('edit');
      Route::put('/{notice}', 'update')->name('update');
      Route::delete('/{notice}', 'destroy')->name('destroy');
      Route::get('/trashed', 'trashed')->name('trashed');
      Route::delete('/{notice}/trash', 'trash')->name('trash');
      Route::post('/{id}/restore', 'restore')->name('restore');
      Route::delete('/{id}/force-delete', 'forceDelete')->name('force-delete');
      Route::get('/status-update/{id}', 'updateStatus')->name('status.update');
    });
  });

  // In web.php or your routes file
  /*   Route::get('/applications', [SeatApplicationController::class, 'adminIndex'])->name('applications.index'); */
  /*   Route::get('/application', [SeatApplicationController::class, 'userIndex'])->name('application.index'); */
});
// Seat Applications Routes
Route::prefix('applications')->name('applications.')->middleware(['auth:admin'])->group(function () {
  Route::get('/', [SeatApplicationController::class, 'adminIndex'])->name('index');
  Route::get('/my-applications', [SeatApplicationController::class, 'userIndex'])->name('user.index');
  Route::get('/create', [SeatApplicationController::class, 'create'])->name('create');
  Route::post('/', [SeatApplicationController::class, 'store'])->name('store');
  Route::get('/archive', [SeatApplicationController::class, 'archive'])->name('archive');
  Route::get('/{application}', [SeatApplicationController::class, 'show'])->name('show');
  Route::post('/{application}/process', [SeatApplicationController::class, 'process'])->name('process');

  // Update the destroy route for seat cancellation
  Route::delete('/{application}/cancel', [SeatApplicationController::class, 'destroy'])
       ->name('cancel');  // This will make the full route name 'applications.cancel'
  Route::delete('/{application}/force-delete', [SeatApplicationController::class, 'forceDelete'])
       ->name('force-delete')
       ->withTrashed();  // This is important to allow finding soft deleted models
});

// Problem Management Routes
Route::prefix('problems')->name('problems.')->middleware(['auth:admin'])->group(function() {
  // Specific routes first
  Route::get('/trashed', [ProblemController::class, 'trashed'])->name('trashed');
  Route::delete('/{problem}/trash', [ProblemController::class, 'trash'])->name('trash');
  Route::post('/{id}/restore', [ProblemController::class, 'restore'])->name('restore');
  Route::delete('/{id}/force-delete', [ProblemController::class, 'forceDelete'])->name('force-delete');

  // Then the more generic CRUD routes
  Route::get('/', [ProblemController::class, 'index'])->name('index');
  Route::get('/create', [ProblemController::class, 'create'])->name('create');
  Route::post('/', [ProblemController::class, 'store'])->name('store');
  Route::get('/{problem}', [ProblemController::class, 'show'])->name('show');
  Route::put('/{problem}', [ProblemController::class, 'update'])->name('update');
  Route::post('/{problem}/respond', [ProblemController::class, 'respond'])->name('respond');
  Route::post('/{problem}/close', [ProblemController::class, 'close'])->name('close');

});

/**
 * Frontend routes
 */
Route::get('/', [FrontendPageController::class, 'showHomePage'])->name('home.page');
Route::get('/book', [FrontendPageController::class, 'showBookPage'])->name('book.page');
Route::get('room-photos/{filename}', [App\Http\Controllers\PublicFileController::class, 'showRoomPhoto'])->name('room.photo');

Route::controller(HallBookingController::class)->prefix('hall-bookings')->name('hall-booking.')->group(function () {
  Route::get('/', 'index')->name('index');
  Route::get('/{room}', 'booking')->name('booking');
});

// Frontend Notice Routes (add these with your other public routes)
Route::controller(FrontendNoticeController::class)->prefix('notices')->name('notices.')->group(function() {
  Route::get('/', 'index')->name('index');
  Route::get('/{notice}', 'show')->name('show');
});

// Update the booking route to use auth middleware
/* Route::get('/hall-bookings/{room}', [HallBookingController::class, 'booking'])
 *      ->middleware('auth')
 *      ->name('hall-booking.book'); */
