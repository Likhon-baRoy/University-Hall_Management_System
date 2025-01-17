<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
    <title>City University Hall Portal - Reset Password</title>

    <!-- Include your existing CSS files -->
    <link rel="shortcut icon" type="image/x-icon" href="{{asset('admin/assets/img/favicon.png')}}">
    <link rel="stylesheet" href="{{asset('admin/assets/css/bootstrap.min.css')}}">
    <link rel="stylesheet" href="{{asset('admin/assets/css/font-awesome.min.css')}}">
    <link rel="stylesheet" href="{{asset('admin/assets/css/style.css')}}">
  </head>
  <body>
    <div class="main-wrapper login-body">
      <div class="login-wrapper">
        <div class="container">
          <div class="loginbox">
            <div class="login-left">
              <img class="img-fluid" src="{{ asset('admin/assets/img/cu-logo.png') }}" alt="Logo">
            </div>
            <div class="login-right">
              <div class="login-right-wrap">
                <h1>Reset Password</h1>
                <p class="account-subtitle">Enter your new password</p>

                @include('validate')

                <form action="{{ route('password.update') }}" method="POST">
                  @csrf
                  <input type="hidden" name="token" value="{{ $token }}">

                  <div class="form-group">
                    <input type="email" name="email" class="form-control" placeholder="Email">
                  </div>
                  <div class="form-group">
                    <input type="password" name="password" class="form-control" placeholder="New Password">
                  </div>
                  <div class="form-group">
                    <input type="password" name="password_confirmation" class="form-control" placeholder="Confirm Password">
                  </div>
                  <div class="form-group">
                    <button class="btn btn-primary btn-block" type="submit">Reset Password</button>
                  </div>
                </form>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Include your existing JS files -->
    <script src="{{asset('admin/assets/js/jquery-3.2.1.min.js')}}"></script>
    <script src="{{asset('admin/assets/js/popper.min.js')}}"></script>
    <script src="{{asset('admin/assets/js/bootstrap.min.js')}}"></script>
    <script src="{{asset('admin/assets/js/script.js')}}"></script>
  </body>
</html>
