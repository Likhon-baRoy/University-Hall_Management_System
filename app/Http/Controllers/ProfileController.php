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
          Storage::disk('public')->delete('public/image/profile/' . $user->photo);
        }

        $request->validate([
          'photo' => 'image|mimes:jpeg,png,jpg|max:2048'
        ]);

        $photo = $request->file('photo');
        $filename = time() . '.' . $photo->getClientOriginalExtension();
        $photo->storeAs('image/profile', $filename, 'public');

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
    $request->validate([
      'old_pass' => ['required'],
      'pass' => ['required', 'min:8', 'confirmed'],
    ], [
      'old_pass.required' => 'The old password field is required.',
      'pass.required' => 'The new password field is required.',
      'pass.min' => 'The new password must be at least 8 characters.',
      'pass.confirmed' => 'The password confirmation does not match.',
    ]);

    // Check if old password matches
    if (!password_verify($request->old_pass, Auth::guard('admin')->user()->password)) {
      return redirect()
            ->route('profile.index', ['tab' => 'password'])
            ->withErrors(['old_pass' => 'Your old password is incorrect.'])
            ->withInput();
    }

    // Update password
    $data = Admin::findOrFail(Auth::guard('admin')->user()->id);
    $data->update([
      'password' => Hash::make($request->pass),
    ]);

    // Log out admin after password update
    Auth::guard('admin')->logout();

    // Redirect back to login
    return redirect()->route('login')
                     ->with('success', 'Password changed successfully. Please login with your new password.');
  }

  /**
   * Remove the specified resource from storage.
   */
  public function destroy(string $id)
  {
    //
  }
}
