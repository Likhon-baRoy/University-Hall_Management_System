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
    return view('admin.pages.profile', compact('user'));
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
    $request->validate([
      'current_password' => 'required',
      'password' => 'required|string|min:8|confirmed',
    ]);

    $user = Auth::guard('admin')->user();

    if (!Hash::check($request->current_password, $user->password)) {
      return back()->with('error', 'Current password is incorrect');
    }

    try {
      $user->update([
        'password' => Hash::make($request->password)
      ]);

      return redirect()->back()->with('success', 'Password changed successfully!');
    } catch (\Exception $e) {
      return redirect()->back()->with('error', 'Error changing password. Please try again.');
    }
  }

  /**
   * Remove the specified resource from storage.
   */
  public function destroy(string $id)
  {
    //
  }
}
