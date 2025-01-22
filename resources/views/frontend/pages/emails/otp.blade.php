{{-- resources/views/frontend/pages/emails/otp.blade.php --}}
<!DOCTYPE html>
<html>
  <head>
    <title>OTP Verification</title>
    <style>
     body {
       font-family: Arial, sans-serif;
       background-color: #f9f9f9;
       color: #333333;
       margin: 0;
       padding: 0;
     }
     .container {
       max-width: 600px;
       margin: 20px auto;
       background-color: #ffffff;
       border-radius: 8px;
       box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
       overflow: hidden;
       border: 1px solid #e0e0e0;
     }
     .header {
       background-color: #4CAF50;
       color: #ffffff;
       text-align: center;
       padding: 15px 20px;
       font-size: 24px;
     }
     .content {
       padding: 20px;
       text-align: center;
     }
     .content p {
       margin: 10px 0;
       font-size: 16px;
       line-height: 1.6;
     }
     .otp-code {
       display: inline-block;
       margin: 15px 0;
       font-size: 24px;
       font-weight: bold;
       color: #4CAF50;
       padding: 10px 20px;
       border: 1px dashed #4CAF50;
       border-radius: 5px;
       background-color: #f9fff9;
     }
     .footer {
       text-align: center;
       padding: 10px 20px;
       font-size: 14px;
       color: #666666;
       background-color: #f4f4f4;
     }
    </style>
  </head>
  <body>
    <div class="container">
      <div class="header">
        OTP Verification
      </div>
      <div class="content">
        <p>Hello,</p>
        <p>Your OTP code for registration is:</p>
        <div class="otp-code">{{ $otp }}</div>
        <p>This code will expire in <strong>5 minutes</strong>.</p>
      </div>
      <div class="footer">
        <p>If you did not request this, please ignore this email.</p>
      </div>
    </div>
  </body>
</html>
