<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
    <title>City University Hall Portal - Forgot Password</title>

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
              <img class="img-fluid" src="admin/assets/img/cu-logo.png" alt="Logo">
            </div>
            <div class="login-right">
              <div class="login-right-wrap">
                <h1>Forgot Password</h1>
                <p class="account-subtitle">Enter your email to get a password reset link</p>

                @include('validate')

                <form action="{{ route('password.email') }}" method="POST">
                  @csrf
                  <div class="form-group">
                    <input type="email" name="email" class="form-control" placeholder="Email">
                  </div>
                  <div class="form-group">
                    <button class="btn btn-primary btn-block" type="submit">Send Reset Link</button>
                  </div>
                </form>

                <div class="text-center dont-have">
                  Remember your password? <a href="{{ route('login') }}">Login</a>
                </div>
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
