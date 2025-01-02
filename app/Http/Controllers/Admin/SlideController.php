<?php

namespace App\Http\Controllers\Admin;

use App\Models\Slider;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SlideController extends Controller
{
  /**
   * Display a listing of the resource.
   */
  public function index() {

    $sliders = Slider::latest() -> where('trash', false) -> get();

    return view('admin.pages.slider.index', [
      'form_type' => 'create',
      'sliders'   => $sliders
    ]);
  }

  /**
   * Show the form for creating a new resource.
   */
  public function create()
  {
    //
  }

  /**
   * Store a newly created resource in storage.
   */
  public function store(Request $request)
  {
    // validation
    $request -> validate([
      'title'             => 'required',
      'subtitle'          => 'required',
      'photo'             => 'required',
    ]);

    // btn management
    $buttons = [];

    for( $i = 0; $i < count($request -> btn_title) ; $i++ ){
      array_push($buttons, [
        'btn_title' => $request -> btn_title[$i],
        'btn_link'  => $request -> btn_link[$i],
        'btn_type'  =>  $request -> btn_type[$i],
      ]);
    }

    // slider image manage
    if( $request -> hasFile('photo') ){

      $img = $request -> file('photo');
      $file_name = md5(time().rand()) .'.'. $img -> getClientOriginalExtension();

      $img->move(storage_path('app/public/sliders'), $file_name);
    }

    // add new slide
    Slider::create([
      'title'     => $request -> title,
      'subtitle'  => $request -> subtitle,
      'photo'     => $file_name,
      'btns'      => json_encode($buttons)
    ]);

    // return back
    return back() -> with('success' , 'Slide Added successful');
  }

  /**
   * Display the specified resource.
   */
  public function show(string $id)
  {
    //
  }

  /**
   * Show the form for editing the specified resource.
   */
  public function edit(string $id)
  {
    $slider = Slider::findOrFail($id);
    $sliders = Slider::latest() -> get();
    return view('admin.pages.slider.index', [
      'form_type' => 'edit',
      'sliders'   => $sliders,
      'slider'   => $slider,
    ]);
  }

  /**
   * Update the specified resource in storage.
   */
  public function update(Request $request, string $id)
  {
    // get slider
    $slider = Slider::findOrFail($id);

    // btn management
    $buttons = [];

    for( $i = 0; $i < count($request -> btn_title) ; $i++ ){
      array_push($buttons, [
        'btn_title' => $request -> btn_title[$i],
        'btn_link'  => $request -> btn_link[$i],
        'btn_type'  =>  $request -> btn_type[$i],
      ]);
    }

    // update slider
    $slider -> update([
      'title'     => $request -> title,
      'subtitle'  => $request -> subtitle,
      'btns'      => json_encode($buttons)
    ]);

    // return back
    return back() -> with('success' , 'Slide updated successful');
  }

  /**
   * Remove the specified resource from storage.
   */
  public function destroy(string $id) {

    $slider_data = Slider::findOrFail($id);

    $slider_data -> delete();

    // return with a success message
    return back() -> with('success-main', $data -> title . ', deleted permanantly');
  }


  /*****************************************************************
   * Custom Methods Section
   *****************************************************************/
  /**
   * Status update
   */
  public function updateStatus($id) {

    $slider_data = Slider::findOrfail($id);

    if ($slider_data -> status) {

      $slider_data -> update([
        'status'    => false
      ]);

    } else{

      $slider_data -> update([
        'status'    => true
      ]);
    }

    return back() -> with('success-main', $slider_data -> title . ', status update successful');
  }

  /**
   * Trash update
   */
  public function updateTrash($id) {

    $slider_data = Slider::findOrfail($id);

    if ($slider_data -> trash) {

      $slider_data -> update([
        'trash'    => false
      ]);

    } else{

      $slider_data -> update([
        'trash'    => true
      ]);
    }

    // return with a success message
    return back() -> with('success-main', $slider_data -> title . ' data moved to Trash');
  }

  /**
   * Display Trash Users
   */
  public function trashSlider() {

    $slider_data = Slider::latest() -> where('trash', true) -> get();

    return view('admin.pages.slider.trash', [
      'slider_data'      => $slider_data,
      'form_type'     => 'trash',
    ]);
  }
}
