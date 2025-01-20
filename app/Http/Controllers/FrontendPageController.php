<?php

namespace App\Http\Controllers;

use App\Models\Slider;
use Illuminate\Http\Request;

class FrontendPageController extends Controller {
  /* *
   * show Home Page
   */
  public function showHomePage() {
    $sliders = Slider::where('status', true ) -> latest() -> get();

    return view('frontend.pages.home', [
      'sliders'       => $sliders
    ]);
  }

  public function showBookPage() {
    return view('frontend.pages.book');
  }
}
