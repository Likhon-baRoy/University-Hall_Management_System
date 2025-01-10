<?php
// app/Traits/VerifiesUniversityMembers.php

namespace App\Traits;

use App\Models\StudentVerification;
use App\Models\TeacherVerification;
use App\Models\StaffVerification;

trait VerifiesUniversityMembers
{
  protected function verifyUniversityMember($userId, $email, $userType)
  {
    $verificationModel = $this->getVerificationModel($userType);

    if (!$verificationModel) {
      return false;
    }

    // Check if user exists and is not already registered
    $verification = $verificationModel::where([
      'user_id' => $userId,
      'email' => $email,        // Keeps email verification
      'is_registered' => false  // Only allows unregistered users
    ])->first();

    if ($verification) {
      // Mark as registered if found
      $verification->update(['is_registered' => true]);
      return true;
    }

    return false;
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
