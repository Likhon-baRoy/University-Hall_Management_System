<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class FrontendPageController extends Controller {
  /* *
   * show Home Page
   */
  public function showHomePage() {
    return view('frontend.pages.home');
  }

  public function showBookPage() {
    return view('frontend.pages.book');
  }
}
