<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class NotificationController extends Controller
{
  public function index()
  {
    try {
      $user = Auth::guard('admin')->user();
      $notifications = $user->notifications()->paginate(10);

      return view('admin.notifications.index', compact('notifications'));
    } catch (\Exception $e) {
      Log::error('Error in notification index: ' . $e->getMessage());
      return redirect()->back()->with('error', 'Error loading notifications');
    }
  }

  public function markAsRead($id)
  {
    try {
      $user = Auth::guard('admin')->user();
      $notification = $user->notifications()->findOrFail($id);
      $notification->markAsRead();

      return response()->json([
        'success' => true,
        'message' => 'Notification marked as read'
      ]);
    } catch (\Exception $e) {
      Log::error('Error marking notification as read: ' . $e->getMessage());
      return response()->json([
        'success' => false,
        'message' => 'Error marking notification as read'
      ], 500);
    }
  }

  public function markAllAsRead()
  {
    try {
      $user = Auth::guard('admin')->user();
      $user->unreadNotifications->markAsRead();

      return response()->json([
        'success' => true,
        'message' => 'All notifications marked as read'
      ]);
    } catch (\Exception $e) {
      Log::error('Error marking all notifications as read: ' . $e->getMessage());
      return response()->json([
        'success' => false,
        'message' => 'Error marking all notifications as read'
      ], 500);
    }
  }

  public function getUnreadCount()
  {
    try {
      $user = Auth::guard('admin')->user();
      if (!$user) {
        return response()->json([
          'success' => false,
          'message' => 'User not authenticated'
        ], 401);
      }

      $count = $user->unreadNotifications()->count();

      return response()->json([
        'success' => true,
        'count' => $count
      ]);
    } catch (\Exception $e) {
      Log::error('Error getting notification count: ' . $e->getMessage());
      return response()->json([
        'success' => false,
        'message' => 'Error getting notification count'
      ], 500);
    }
  }
}
