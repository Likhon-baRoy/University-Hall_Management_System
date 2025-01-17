<!DOCTYPE html>
<html lang="en">

  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
    <title>City Universit Hall Portall - Login</title>

	<!-- Favicon -->
    <link rel="shortcut icon" type="image/x-icon" href="{{asset('admin/assets/img/favicon.png')}}">

	<!-- Bootstrap CSS -->
    <link rel="stylesheet" href="{{asset('admin/assets/css/bootstrap.min.css')}}">

	<!-- Fontawesome CSS -->
    <link rel="stylesheet" href="{{asset('admin/assets/css/font-awesome.min.css')}}">

	<!-- Main CSS -->
    <link rel="stylesheet" href="{{asset('admin/assets/css/style.css')}}">

  </head>
  <body>

	<!-- Main Wrapper -->
    <div class="main-wrapper login-body">
      <div class="login-wrapper">
        <div class="container">
          <div class="loginbox">
            <div class="login-left">
			  <img class="img-fluid" src="admin/assets/img/cu-logo.png" alt="Logo">
            </div>
            <div class="login-right">
			  <div class="login-right-wrap">
				<h1>Login</h1>
				<p class="account-subtitle">Access to our dashboard</p>

				<!-- Form -->
				<form action="{{ route('login') }}" method="POST">
				  @csrf
				  @include('validate')
				  <div class="form-group">
					<input name="auth" class="form-control" type="text" placeholder="Email/Cell/Username">
				  </div>
				  <div class="form-group">
					<input name = "password" class="form-control" type="password" placeholder="Password">
				  </div>
				  <div class="form-group">
					<button class="btn btn-primary btn-block" type="submit">Login</button>
				  </div>
				</form>
				<!-- /Form -->

				<div class="text-center forgotpass">
                  <a href="{{ route('password.request') }}">Forgot Password?</a>
                </div>

                <div class="login-or">
                  <span class="or-line"></span>
                  <span class="span-or">or</span>
                </div>

                <div class="text-center dont-have">Don't have a seat in hall? <a href="{{ route('hall-booking.index') }}">Book Now</a></div>

			  </div>
            </div>
          </div>
        </div>
      </div>
    </div>
	<!-- /Main Wrapper -->

	<!-- jQuery -->
    <script src="{{asset('admin/assets/js/jquery-3.2.1.min.js')}}"></script>

	<!-- Bootstrap Core JS -->
    <script src="{{asset('admin/assets/js/popper.min.js')}}"></script>
    <script src="{{asset('admin/assets/js/bootstrap.min.js')}}"></script>

	<!-- Custom JS -->
	<script src="{{asset('admin/assets/js/script.js')}}"></script>

  </body>
</html>
