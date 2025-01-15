<?php

namespace App\Http\Controllers;

use App\Models\Notice;

class NoticeController extends Controller
{
  public function index()
  {
    $latestNotice = Notice::where('status', true)
                          ->whereNull('deleted_at')
                          ->latest('published_at')
                          ->first();

    $recentNotices = Notice::where('status', true)
                           ->whereNull('deleted_at')
                           ->latest('published_at')
                           ->take(10)
                           ->get();

    return view('frontend.pages.notices.index', compact('latestNotice', 'recentNotices'));
  }

  public function show(Notice $notice)
  {
    if (!$notice->status) {
      abort(404);
    }

    $recentNotices = Notice::where('status', true)
                           ->whereNull('deleted_at')
                           ->latest('published_at')
                           ->take(10)
                           ->get();

    return view('frontend.pages.notices.show', [
      'currentNotice' => $notice,
      'recentNotices' => $recentNotices
    ]);
  }
}
