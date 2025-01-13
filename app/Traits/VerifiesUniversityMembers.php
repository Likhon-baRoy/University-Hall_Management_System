<?php
namespace App\Traits;

use App\Models\Hall;
use App\Models\StudentVerification;
use App\Models\TeacherVerification;
use App\Models\StaffVerification;
use Illuminate\Validation\ValidationException;

trait VerifiesUniversityMembers
{
  protected function verifyUniversityMember($userId, $email, $userType, $hallId)
  {
    // Get the verification model based on user type
    $verificationModel = $this->getVerificationModel($userType);
    if (!$verificationModel) {
      throw ValidationException::withMessages([
        'credentials' => ['Invalid user type specified.']
      ]);
    }

    // Find the user in verification table
    $verification = $verificationModel::where([
      'user_id' => $userId,
      'email' => $email,
      'is_registered' => false
    ])->first();

    if (!$verification) {
      throw ValidationException::withMessages([
        'credentials' => ['Invalid university credentials. Please ensure you are using your official university ID and email.']
      ]);
    }

    // Get hall details
    $hall = Hall::findOrFail($hallId);

    // Verify gender match
    if ($verification->gender !== $hall->gender) {
      throw ValidationException::withMessages([
        'gender_mismatch' => ["You cannot register for {$hall->name} as it is a {$hall->gender}'s hall."]
      ]);
    }

    // If all verifications pass, mark as registered
    $verification->update(['is_registered' => true]);

    return true;
  }

  protected function getVerificationModel($userType)
  {
    return match ($userType) {
      'student' => StudentVerification::class,
      'teacher' => TeacherVerification::class,
      'staff' => StaffVerification::class,
      default => null
    };
  }
}
