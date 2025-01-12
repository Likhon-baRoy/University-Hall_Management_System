<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
  /**
   * Display a listing of the resource.
   */
  public function index() {
    $user = Auth::guard('admin')->user();
    return view('admin.pages.profile.index', compact('user'));
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
    //
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
    //
  }

  /**
   * Update the specified resource in storage.
   */
  public function update(Request $request, string $id)
  {
    // Validate request data
    $request->validate([
      'name' => 'required|string|max:255',
      'cell' => [
        'required',
        'string',
        Rule::unique('admins')->ignore($id),
      ],
      'dob' => 'nullable|date',
      'address' => 'nullable|string|max:255',
      'bio' => 'nullable|string|max:1000',
    ]);

    try {
      $user = Admin::findOrFail($id);

      // Update user data
      $user->update([
        'name' => $request->name,
        'cell' => $request->cell,
        'dob' => $request->dob,
        'address' => $request->address,
        'bio' => $request->bio,
      ]);

      // Handle photo upload if included
      if ($request->hasFile('photo')) {
        // Delete old photo if it exists and isn't the default
        if ($user->photo && $user->photo !== 'avatar.png') {
          Storage::disk('public')->delete('public/img/' . $user->photo);
        }

        $request->validate([
          'photo' => 'image|mimes:jpeg,png,jpg|max:2048'
        ]);

        $photo = $request->file('photo');
        $filename = time() . '.' . $photo->getClientOriginalExtension();
        $photo->storeAs('img', $filename, 'public');

        $user->update(['photo' => $filename]);
      }

      return redirect()->back()->with('success', 'Profile updated successfully!');
    } catch (\Exception $e) {
      return redirect()->back()->with('error', 'Error updating profile. Please try again.');
    }
  }

  /**
   * Update user's password.
   */
  public function updatePassword(Request $request)
  {
    // check old password
    if ( !(password_verify($request -> old_pass, Auth::guard('admin') -> user() -> password)) ) {
      return back() -> with('danger', 'User old password didn\'t match');
    }

    //new password confirmation
    if ( $request -> pass != $request -> pass_confirmation) {
      return back() -> with('warning', 'Password confirmation failed');
    }

    // find the user data
    $data = Admin::findOrFail(Auth::guard('admin') -> user() -> id);

    // Update new password
    $data -> update([
      'password' => Hash::make($request -> pass)
    ]);

    Auth::guard('admin') -> logout();

    return redirect() -> route('login') -> with('success', 'user password changed successfully');
  }

  /**
   * Remove the specified resource from storage.
   */
  public function destroy(string $id)
  {
    //
  }
}
