<?php

namespace App\Http\Controllers;

use App\Models\Problem;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;
use App\Notifications\NewProblemNotification;
use App\Notifications\ProblemResponseNotification;

class ProblemController extends Controller
{
  /**
   * Display a listing of problems based on user role.
   */
  public function index()
  {
    $user = Auth::guard('admin')->user();
    $permissions = json_decode($user->role->permissions ?? '[]');

    // Start with base query
    $query = in_array('problems', $permissions)
    ? Problem::with(['user', 'handledBy'])
           : Problem::where('user_id', $user->id);

    // Apply filters
    if (request('status')) {
      $query->where('status', request('status'));
    }
    if (request('priority')) {
      $query->where('priority', request('priority'));
    }
    if (request('issue_type')) {
      $query->where('issue_type', request('issue_type'));
    }

    $problems = $query->latest()->paginate(10);

    // Get counts for summary
    $counts = [
      'total' => Problem::count(),
      'pending' => Problem::where('status', 'pending')->count(),
      'resolved' => Problem::whereIn('status', ['resolved', 'closed'])->count(),
      'trashed' => Problem::onlyTrashed()->count(),
    ];

    // Choose view based on user role
    $view = in_array('problems', $permissions)
    ? 'admin.pages.problems.admin-index'
          : 'admin.pages.problems.user-index';

    return view($view, compact('problems', 'counts'));
  }

  /**
   * Show form to create a new problem (only for regular users)
   */
  public function create()
  {
    $user = Auth::guard('admin')->user();
    $permissions = json_decode($user->role->permissions ?? '[]');

    // Prevent admins from creating problems
    if (in_array('problems', $permissions)) {
      return redirect()->route('problems.index')
                       ->with('error', 'Administrators cannot create problems');
    }

    return view('admin.pages.problems.create');
  }

  /**
   * Display problem details
   */
  public function show(Problem $problem)
  {
    $user = Auth::guard('admin')->user();
    $permissions = json_decode($user->role->permissions ?? '[]');

    // Check if user has permission or owns the problem
    if (!in_array('problems', $permissions) && $problem->user_id !== $user->id) {
      return redirect()->route('problems.index')
                       ->with('error', 'You do not have permission to view this problem.');
    }

    return view('admin.pages.problems.show', compact('problem'));
  }

  /**
   * Update problem status and response
   */
  public function update(Request $request, Problem $problem)
  {
    $user = Auth::guard('admin')->user();
    $permissions = json_decode($user->role->permissions ?? '[]');

    // Check if user has admin permissions
    if (!in_array('problems', $permissions)) {
      return redirect()->back()
                       ->with('error', 'You do not have permission to update problems.');
    }

    $request->validate([
      'status' => 'required|in:pending,in_progress,resolved,closed',
      'admin_response' => 'required|string'
    ]);

    try {
      $problem->update([
        'status' => $request->status,
        'admin_response' => $request->admin_response,
        'handled_by' => $user->id,
        'admin_responded_at' => now()
      ]);

      // Notify the problem owner
      $problemOwner = $problem->user; // Assuming `user` is a relationship in the `Problem` model
      $problemOwner->notify(new ProblemResponseNotification(
        $problem,
        $request->admin_response,
        $user->name
      ));

      return redirect()->route('problems.show', $problem)
                       ->with('success', 'Problem updated successfully!');
    } catch (\Exception $e) {
      return redirect()->back()
                       ->with('error', 'Error updating problem: ' . $e->getMessage());
    }
  }


  /**
   * Admin response to problem with notification
   */
  public function respond(Request $request, Problem $problem)
  {
    try {
      $user = Auth::guard('admin')->user();
      $permissions = json_decode($user->role->permissions ?? '[]');

      if (!in_array('problems', $permissions)) {
        return redirect()->back()
                         ->with('error', 'You do not have permission to respond to problems.');
      }

      $request->validate([
        'admin_response' => 'required|string',
        'status' => 'required|in:in_progress,resolved,closed'
      ]);

      $problem->update([
        'admin_response' => $request->admin_response,
        'status' => $request->status,
        'handled_by' => $user->id,
        'admin_responded_at' => now()
      ]);

      try {
        $problem->user->notify(new ProblemResponseNotification($problem, $request->admin_response));
        Log::info('Response notification sent to user', [
          'problem_id' => $problem->id,
          'user_id' => $problem->user->id
        ]);
      } catch (\Exception $e) {
        Log::error('Failed to send response notification', [
          'problem_id' => $problem->id,
          'error' => $e->getMessage()
        ]);
      }

      return redirect()->route('problems.show', $problem)
                       ->with('success', 'Response added successfully');

    } catch (\Exception $e) {
      Log::error('Error responding to problem', [
        'problem_id' => $problem->id,
        'error' => $e->getMessage()
      ]);
      return redirect()->back()
                       ->with('error', 'An error occurred while responding to the problem.');
    }
  }

  /**
   * Close a resolved problem
   */
  public function close(Problem $problem)
  {
    $user = Auth::guard('admin')->user();
    $permissions = json_decode($user->role->permissions ?? '[]');

    // Verify admin permissions
    if (!in_array('problems', $permissions)) {
      return redirect()->back()
                       ->with('error', 'You do not have permission to close problems.');
    }

    if ($problem->status !== 'resolved') {
      return back()->with('error', 'Only resolved problems can be closed');
    }

    $problem->update(['status' => 'closed']);

    return redirect()->route('problems.index')
                     ->with('success', 'Problem closed successfully');
  }

  /**
   * Check and reset daily post limit
   */
  private function checkDailyPostLimit($user)
  {
    // Reset count if it's a new day
    if (!$user->posts_count_reset_at || $user->posts_count_reset_at->isPast()) {
      $user->update([
        'daily_problem_posts' => 0,
        'posts_count_reset_at' => now()->addDay()
      ]);
    }

    // Check if user has reached daily limit (e.g., 3 posts per day)
    if ($user->daily_problem_posts >= 3) {
      return false;
    }

    return true;
  }

  /**
   * Store a new problem with daily limit check and proper notification
   */
  public function store(Request $request)
  {
    try {
      $user = Auth::guard('admin')->user();

      // Check daily post limit
      if (!$this->checkDailyPostLimit($user)) {
        return redirect()->route('problems.index')
                         ->with('error', 'You have reached your daily limit for reporting problems.');
      }

      // Validate and create problem
      $request->validate([
        'title' => 'required|string|max:255',
        'description' => 'required|string',
        'issue_type' => 'required|in:hall,room,seat,other',
        'location' => 'required|string|max:255',
        'priority' => 'required|in:low,medium,high,urgent',
      ]);

      $problem = Problem::create([
        'user_id' => $user->id,
        'title' => $request->title,
        'description' => $request->description,
        'issue_type' => $request->issue_type,
        'location' => $request->location,
        'priority' => $request->priority,
        'status' => 'pending'
      ]);

      // Increment user's daily post count
      $user->increment('daily_problem_posts');

      // Notify all eligible admins
      Admin::whereHas('role', function ($query) {
        $query->whereJsonContains('permissions', 'problems');
      })->chunk(100, function ($admins) use ($problem) {
        Notification::send($admins, new NewProblemNotification($problem));
      });

      return redirect()->route('problems.show', $problem)
                       ->with('success', 'Problem reported successfully!');

    } catch (\Exception $e) {
      Log::error('Error creating problem', [
        'error' => $e->getMessage(),
        'user_id' => $user->id ?? null
      ]);
      return redirect()->route('problems.index')
                       ->with('error', 'An error occurred while creating the problem.');
    }
  }

  /**
   * Move problem to trash (soft delete)
   */
  public function trash(Problem $problem)
  {
    $user = Auth::guard('admin')->user();
    $permissions = json_decode($user->role->permissions ?? '[]');

    if (!in_array('problems', $permissions)) {
      return redirect()->back()
                       ->with('error', 'You do not have permission to delete problems.');
    }

    $problem->delete();

    return redirect()->route('problems.index')
                     ->with('success', 'Problem moved to trash.');
  }

  /**
   * Display trashed problems
   */
  public function trashed()
  {
    $user = Auth::guard('admin')->user();
    $permissions = json_decode($user->role->permissions ?? '[]');

    // Add debugging
    \Log::info('Trashed method accessed');
    \Log::info('User permissions:', ['permissions' => $permissions]);

    if (!in_array('problems', $permissions)) {
      \Log::warning('Permission denied for user:', ['user_id' => $user->id]);
      return redirect()->back()
                       ->with('error', 'You do not have permission to view trashed problems.');
    }

    try {
      $problems = Problem::onlyTrashed()
                         ->with(['user', 'handledBy'])
                         ->latest()
                         ->paginate(10);

      \Log::info('Retrieved trashed problems:', ['count' => $problems->count()]);

      return view('admin.pages.problems.trashed', compact('problems'));
    } catch (\Exception $e) {
      \Log::error('Error in trashed problems:', ['error' => $e->getMessage()]);
      return redirect()->back()
                       ->with('error', 'An error occurred while retrieving trashed problems.');
    }
  }

  /**
   * Restore trashed problem
   */
  public function restore($id)
  {
    $user = Auth::guard('admin')->user();
    $permissions = json_decode($user->role->permissions ?? '[]');

    if (!in_array('problems', $permissions)) {
      return redirect()->back()
                       ->with('error', 'You do not have permission to restore problems.');
    }

    $problem = Problem::onlyTrashed()->findOrFail($id);
    $problem->restore();

    return redirect()->route('problems.trashed')
                     ->with('success', 'Problem restored successfully.');
  }

  /**
   * Permanently delete problem
   */
  public function forceDelete($id)
  {
    $user = Auth::guard('admin')->user();
    $permissions = json_decode($user->role->permissions ?? '[]');

    if (!in_array('problems', $permissions)) {
      return redirect()->back()
                       ->with('error', 'You do not have permission to permanently delete problems.');
    }

    $problem = Problem::onlyTrashed()->findOrFail($id);
    $problem->forceDelete();

    return redirect()->route('problems.trashed')
                     ->with('success', 'Problem permanently deleted.');
  }
}
