<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Notice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class NoticeController extends Controller
{
  public function index()
  {
    $notices = Notice::with('creator')
                     ->whereNull('deleted_at')
                     ->latest()
                     ->paginate(10);

    return view('admin.pages.notices.index', compact('notices'));
  }

  public function create()
  {
    return view('admin.pages.notices.create');
  }

  /**
   * Toggle the status of a notice
   */
  public function toggleStatus(Request $request, Notice $notice)
  {
    try {
      $notice->status = $request->status;
      $notice->save();

      return response()->json([
        'success' => true,
        'message' => 'Notice status updated successfully'
      ]);
    } catch (\Exception $e) {
      return response()->json([
        'success' => false,
        'message' => 'Failed to update notice status'
      ], 500);
    }
  }

  /**
   * Override the original store method to handle status properly
   */
  public function store(Request $request)
  {
    $validated = $request->validate([
      'title' => 'required|max:255',
      'description' => 'nullable',
      'notice_file' => 'required|file|mimes:pdf,doc,docx|max:10240',
      'expired_at' => 'nullable|date|after:today',
    ]);

    try {
      // Handle file upload first
      $file = $request->file('notice_file');
      $fileName = $file->getClientOriginalName();
      $path = $file->store('notices', 'public');

      // Create the notice with all required fields
      $notice = Notice::create([
        'title' => $validated['title'],
        'description' => $validated['description'],
        'file_path' => $path,
        'file_name' => $fileName,
        'file_size' => $file->getSize(),
        'file_type' => $file->getClientOriginalExtension(),
        'status' => $request->has('status'), // This will be true if checkbox is checked
        'is_featured' => $request->has('is_featured'), // This will be true if checkbox is checked
        'expired_at' => $validated['expired_at'],
        'created_by' => auth('admin')->id(),
        'published_at' => now(),
      ]);

      return redirect()
            ->route('admin.notices.index')
            ->with('success', 'Notice created successfully');
    } catch (\Exception $e) {
      return back()
            ->with('error', 'Failed to create notice: ' . $e->getMessage())
            ->withInput();
    }
  }

  public function update(Request $request, Notice $notice)
  {
    $request->validate([
      'title' => 'required|string|max:255',
      'description' => 'nullable|string',
      'notice_file' => 'nullable|mimes:pdf,doc,docx|max:10240',
      'status' => 'boolean'
    ]);

    if ($request->hasFile('notice_file')) {
      // Delete old file
      Storage::disk('public')->delete($notice->file_path);
      // Store new file
      $filePath = $request->file('notice_file')->store('notices', 'public');
      $notice->file_path = $filePath;
    }

    $notice->update([
      'title' => $request->title,
      'description' => $request->description,
      'status' => $request->status ?? $notice->status
    ]);

    return redirect()->route('admin.notices.index')
                     ->with('success', 'Notice updated successfully');
  }

  public function trash(Notice $notice)
  {
    $notice->delete();
    return redirect()->route('admin.notices.index')
                     ->with('success', 'Notice moved to trash');
  }

  public function trashed()
  {
    $notices = Notice::onlyTrashed()
                     ->latest()
                     ->paginate(10);
    return view('admin.pages.notices.trash', compact('notices'));
  }

  public function restore($id)
  {
    $notice = Notice::onlyTrashed()->findOrFail($id);
    $notice->restore();
    return redirect()->route('admin.notices.trashed')
                     ->with('success', 'Notice restored successfully');
  }

  public function forceDelete($id)
  {
    $notice = Notice::onlyTrashed()->findOrFail($id);
    Storage::disk('public')->delete($notice->file_path);
    $notice->forceDelete();
    return redirect()->route('admin.notices.trashed')
                     ->with('success', 'Notice permanently deleted');
  }

  public function updateStatus($id)
  {
    $notice = Notice::findOrFail($id);
    $notice->status = !$notice->status;
    $notice->save();
    return redirect()
            ->back()
            ->with("success", "Notice status updated successfully");
  }
}
