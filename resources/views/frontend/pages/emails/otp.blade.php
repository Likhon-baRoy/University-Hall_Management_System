{{-- resources/views/frontend/pages/emails/otp.blade.php --}}
<!DOCTYPE html>
<html>
  <head>
    <title>OTP Verification</title>
  </head>
  <body>
    <h1>Your OTP Code</h1>
    <p>Your OTP code for registration is: <strong>{{ $otp }}</strong></p>
    <p>This code will expire in 5 minutes.</p>
  </body>
</html>
