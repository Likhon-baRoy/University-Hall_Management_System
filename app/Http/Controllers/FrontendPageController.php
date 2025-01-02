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
}
