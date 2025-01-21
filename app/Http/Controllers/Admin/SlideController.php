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
    $request->validate([
      'title' => 'required',
      'subtitle' => 'required',
      'description' => 'required',
      'photo' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
    ]);

    // Button management
    $buttons = [];
    if($request->has('btn_title')) {
      for($i = 0; $i < count($request->btn_title); $i++) {
        array_push($buttons, [
          'btn_title' => $request->btn_title[$i],
          'btn_link' => $request->btn_link[$i],
          'btn_type' => $request->btn_type[$i],
        ]);
      }
    }

    // Upload photo
    $fileName = null;
    if($request->hasFile('photo')) {
      $file = $request->file('photo');
      $fileName = md5(time().rand()) .'.'. $file->getClientOriginalExtension();
      $file->move(storage_path('app/public/image/slider'), $fileName);
    }

    // Create slider
    Slider::create([
      'title' => $request->title,
      'subtitle' => $request->subtitle,
      'description' => $request->description,
      'photo' => $fileName,
      'btns' => json_encode($buttons)
    ]);

    return back()->with('success', 'Slide added successfully');
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
  public function edit(string $id) {

    $slider = Slider::findOrFail($id);

    $sliders = Slider::latest() -> get();

    return view('admin.pages.slider.index', [
      'form_type' => 'edit',
      'sliders'   => $sliders,
      'slider'    => $slider,
    ]);
  }

  /**
   * Update the specified resource in storage.
   */
  public function update(Request $request, string $id)
  {
    // Get slider
    $slider = Slider::findOrFail($id);

    // Validation
    $request->validate([
      'title' => 'required',
      'subtitle' => 'required',
      'description' => 'required',
      'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
    ]);

    // Button management
    $buttons = [];
    if($request->has('btn_title')) {
      for($i = 0; $i < count($request->btn_title); $i++) {
        array_push($buttons, [
          'btn_title' => $request->btn_title[$i],
          'btn_link' => $request->btn_link[$i],
          'btn_type' => $request->btn_type[$i],
        ]);
      }
    }

    // Handle photo update
    $fileName = $slider->photo;
    if($request->hasFile('photo')) {
      // Delete old photo
      $oldPhotoPath = storage_path('app/public/image/slider/' . $slider->photo);
      if(file_exists($oldPhotoPath)) {
        unlink($oldPhotoPath);
      }

      // Upload new photo
      $file = $request->file('photo');
      $fileName = md5(time().rand()) .'.'. $file->getClientOriginalExtension();
      $file->move(storage_path('app/public/image/slider'), $fileName);
    }

    // Update slider
    $slider->update([
      'title' => $request->title,
      'subtitle' => $request->subtitle,
      'description' => $request->description,
      'photo' => $fileName,
      'btns' => json_encode($buttons)
    ]);

    return redirect()->route('slider.index')->with('success', 'Slide updated successfully');
  }

  /**
   * Remove the specified resource from storage.
   */
  public function destroy(string $id)
  {
    $slider = Slider::findOrFail($id);

    // Delete photo from storage
    $photoPath = storage_path('app/public/image/slider/' . $slider->photo);
    if(file_exists($photoPath)) {
      unlink($photoPath);
    }

    $slider->delete();

    return back()->with('success-main', 'Slider deleted permanently');
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

    return back() -> with('success-main', $slider_data -> hall . ', status update successful');
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
    return back() -> with('success-main', $slider_data -> hall . ' data moved to Trash');
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
