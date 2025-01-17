<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\EmailOtp;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\OtpEmail;

class OtpController extends Controller
{
  public function sendOtp(Request $request)
  {
    $request->validate([
      'email' => 'required|email'
    ]);

    // Generate 6-digit OTP
    $otp = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);

    // Delete any existing OTPs for this email
    EmailOtp::where('email', $request->email)->delete();

    // Create new OTP record
    $emailOtp = EmailOtp::create([
      'email' => $request->email,
      'otp' => $otp,
      'expires_at' => now()->addMinutes(5)
    ]);

    // Send OTP email
    Mail::to($request->email)->send(new OtpEmail($otp));

    return response()->json([
      'message' => 'OTP sent successfully',
      'expires_at' => $emailOtp->expires_at
    ]);
  }

  public function verifyOtp(Request $request)
  {
    $request->validate([
      'email' => 'required|email',
      'otp' => 'required|string|size:6'
    ]);

    $emailOtp = EmailOtp::where('email', $request->email)
                        ->where('otp', $request->otp)
                        ->where('verified', false)
                        ->where('expires_at', '>', now())
                        ->first();

    if (!$emailOtp) {
      return response()->json([
        'message' => 'Invalid or expired OTP'
      ], 400);
    }

    $emailOtp->update(['verified' => true]);

    return response()->json([
      'message' => 'OTP verified successfully'
    ]);
  }
}
