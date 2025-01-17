<?php

namespace App\Mail;

use Illuminate\Mail\Mailable;

class OtpEmail extends Mailable
{
  public $otp;

  public function __construct($otp)
  {
    $this->otp = $otp;
  }

  public function build()
  {
    return $this->view('frontend.pages.emails.otp')
                ->subject('Your OTP for Registration');
  }
}
