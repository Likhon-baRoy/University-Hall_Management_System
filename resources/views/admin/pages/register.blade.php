<!DOCTYPE html>
<html lang="en">

  <head>
    <meta charset="utf-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
    <title>Registration Page</title>

    <!-- Favicon -->
    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('admin/assets/img/favicon.png') }}">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="{{ asset('admin/assets/css/bootstrap.min.css') }}">
    <link href="{{ asset('https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css') }}" rel="stylesheet">

    <!-- Fontawesome CSS -->
    <link rel="stylesheet" href="{{ asset('admin/assets/css/font-awesome.min.css') }}">

    <!-- Main CSS -->
    <link rel="stylesheet" href="{{ asset('admin/assets/css/style.css') }}">


	<!--[if lt IE 9]>
	  <script src="assets/js/html5shiv.min.js"></script>
	  <script src="assets/js/respond.min.js"></script>
	<![endif]-->
  </head>
  <body>

	<!-- Main Wrapper -->
    <div class="main-wrapper login-body">
      <div class="login-wrapper">
        <div class="container">
          <!-- Centered Header Section -->
          <div class="text-center mb-4">
            <h2 class="gradient-text" style="font-size: 2rem;">Register</h2>
            <hr class="styled-hr" />
            <p class="gradient-text" style="font-size: 1rem; margin-top: 5px;">Access to our dashboard</p>
          </div>

          <div class="loginbox">
            <div class="login-left">
	          <img class="img-fluid" src="{{ url('admin/assets/img/cu-logo.png') }}" alt="Logo">
            </div>
            <div class="login-right">
	          <div class="login-right-wrap">

	            <!-- Form -->
	            <form action="{{ route('register.submit') }}" method="POST">
                  @csrf
                  @include('validate')

                  @if ($errors->has('hall'))
                    <div class="alert alert-danger">
                      {{ $errors->first('hall') }}
                    </div>
                  @endif

                  <!-- Name -->
                  <div class="form-group mb-3">
                    <input class="form-control" type="text" name="name" placeholder="Full Name" value="{{ old('name') }}" required>
                  </div>

                  <!-- User ID -->
                  <div class="form-group mb-3">
                    <input class="form-control" type="text" name="user_id" placeholder="User ID" value="{{ old('user_id') }}" required>
                  </div>

                  <!-- Username -->
                  <div class="form-group mb-3">
                    <input class="form-control" type="text" name="username" placeholder="Username" value="{{ old('username') }}" required>
                  </div>

                  <!-- Email with OTP -->
                  <div class="form-group mb-3">
                    <div class="input-group">
                      <input class="form-control" type="email" name="email" id="email" placeholder="Email" value="{{ old('email') }}" required>
                      <button type="button" class="btn btn-secondary" id="sendOtpBtn">Send OTP</button>
                    </div>
                    <div id="otpTimer" class="text-muted small mt-1" style="display: none;">
                      Time remaining: <span id="timer">5:00</span>
                    </div>
                  </div>

                  <div class="form-group mb-3">
                    <input class="form-control" type="text" name="otp" id="otpInput" placeholder="Enter OTP" readonly required>
                    <div class="invalid-feedback" id="otpError"></div>
                  </div>

                  <!-- Cell -->
                  <div class="form-group mb-3">
                    <input class="form-control" type="text" name="cell" placeholder="Cell" value="{{ old('cell') }}" required>
                  </div>

                  <!-- Password -->
                  <div class="form-group mb-3">
                    <input class="form-control" type="password" name="password" placeholder="Password" required>
                  </div>

                  <!-- Confirm Password -->
                  <div class="form-group mb-3">
                    <input class="form-control" type="password" name="password_confirmation" placeholder="Confirm Password" required>
                  </div>

                  <!-- User Type Selection -->
                  <div class="form-group mb-3">
                    <select class="form-select" name="user_type" required>
                      <option value="">Select User Type</option>
                      @foreach($allowedUserTypes as $type)
                        <option value="{{ $type }}" {{ old('user_type') == $type ? 'selected' : '' }}>
                          {{ ucfirst($type) }}
                        </option>
                      @endforeach
                    </select>
                  </div>

                  <!-- Gender -->
                  <div class="form-group mb-3">
                    <div class="d-flex align-items-center gap-3">
                      <div class="form-check">
                        <input class="form-check-input" type="radio" name="gender" value="male" {{ old('gender') == 'male' ? 'checked' : '' }} required>
                        <label class="form-check-label">Male</label>
                      </div>
                      <div class="form-check">
                        <input class="form-check-input" type="radio" name="gender" value="female" {{ old('gender') == 'female' ? 'checked' : '' }}>
                        <label class="form-check-label">Female</label>
                      </div>
                    </div>
                  </div>

                  <!-- Department -->
                  <div class="form-group mb-3">
                    <select class="form-select" name="dept" required>
                      <option value="">Select Department</option>
                      <option value="CSE" {{ old('dept') == 'CSE' ? 'selected' : '' }}>CSE</option>
                      <option value="EEE" {{ old('dept') == 'EEE' ? 'selected' : '' }}>EEE</option>
                      <option value="ME" {{ old('dept') == 'ME' ? 'selected' : '' }}>ME</option>
                      <option value="BBA" {{ old('dept') == 'BBA' ? 'selected' : '' }}>BBA</option>
                      <option value="PHY" {{ old('dept') == 'PHY' ? 'selected' : '' }}>PHY</option>
                    </select>
                  </div>

                  <!-- Semester Type -->
                  <div class="form-group mb-3">
                    <select class="form-select" name="semester_type" required>
                      <option value="">Select Semester Type</option>
                      <option value="trimester" {{ old('semester_type') == 'trimester' ? 'selected' : '' }}>Trimester (4 Months)</option>
                      <option value="bi-semester" {{ old('semester_type') == 'bi-semester' ? 'selected' : '' }}>Bi-Semester (6 Months)</option>
                    </select>
                  </div>

                  <!-- Semester -->
                  <div class="form-group mb-3">
                    <select class="form-select" name="semester" required>
                      <option value="">Select Semester</option>
                      <option value="summer" {{ old('semester') == 'summer' ? 'selected' : '' }}>Summer</option>
                      <option value="fall" {{ old('semester') == 'fall' ? 'selected' : '' }}>Fall</option>
                      <option value="winter" {{ old('semester') == 'winter' ? 'selected' : '' }}>Winter</option>
                    </select>
                  </div>

                  <!-- Semester Year -->
                  <div class="form-group mb-3">
                    <select class="form-select" name="semester_year" required>
                      <option value="">Select Year</option>
                      @for ($year = date('Y'); $year <= date('Y') + 5; $year++)
                        <option value="{{ $year }}" {{ old('semester_year') == $year ? 'selected' : '' }}>{{ $year }}</option>
                      @endfor
                    </select>
                  </div>

                  <!-- Hall, Room, Seat Section -->
                  <div class="row mb-3">
                    <!-- Hall -->
                    <div class="col-md-4">
                      <div class="form-group">
                        <label>Hall</label>
                        <input class="form-control" type="text" name="hall"
                               value="{{ $bookingData['hall'] ?? '' }}"
                               {{ isset($bookingData['hall']) ? 'readonly' : '' }}>
                        <!-- Debug info -->
                        @if(config('app.debug'))
                          <small class="text-muted">{{ $bookingData['hall'] ?? 'none' }}</small>
                        @endif
                      </div>
                    </div>

                    <!-- Room -->
                    <div class="col-md-4">
                      <div class="form-group">
                        <label>Room</label>
                        <input class="form-control" type="text" name="room"
                               value="{{ $bookingData['room'] ?? '' }}"
                               {{ isset($bookingData['room']) ? 'readonly' : '' }}>
                        <input type="hidden" name="room_id" value="{{ $bookingData['room_id'] ?? '' }}">
                        <!-- Debug info -->
                        @if(config('app.debug'))
                          <small class="text-muted">Room No: {{ $bookingData['room'] ?? 'none' }}</small>
                        @endif
                      </div>
                    </div>

                    <!-- Seat -->
                    <div class="col-md-4">
                      <div class="form-group">
                        <label>Seat</label>
                        <select class="form-select" name="seat" {{ empty($bookingData['room_id']) ? 'disabled' : '' }}>
                          <option value="">Select Seat</option>
                          @if(!empty($bookingData['available_seats']))
                            @foreach($bookingData['available_seats'] as $seat)
                              <option value="{{ $seat }}">{{ $seat }}</option>
                            @endforeach
                          @endif
                        </select>
                        <!-- Debug info -->
                        @if(config('app.debug'))
                          <small class="text-muted">Available seats: {{ !empty($bookingData['available_seats']) ? count($bookingData['available_seats']) : '0' }}</small>
                        @endif
                      </div>
                    </div>
                  </div>

                  <!-- Submit Button -->
                  <div class="form-group mb-0">
                    <button class="btn btn-primary btn-block" type="submit">Register</button>
                  </div>

	            </form>
	            <!-- /Form -->

	            <div class="login-or">
		          <span class="or-line"></span>
		          <span class="span-or">or</span>
	            </div>

	            <div class="text-center dont-have">Already have an account? <a href="{{ route('admin.login.page') }}">Login</a></div>
	          </div>
            </div>
          </div>
        </div>
      </div>
    </div>
	<!-- /Main Wrapper -->

	<script src="{{ asset('admin/assets/js/jquery-3.2.1.min.js') }}"></script>
    <script src="{{ asset('admin/assets/js/popper.min.js') }}"></script>
    <script src="{{ asset('admin/assets/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('admin/assets/js/script.js') }}"></script>
    <script src="{{ asset('custom/otp.js') }}" defer></script>

    <!-- Custom jQuery -->
    <script>
     document.addEventListener('DOMContentLoaded', function() {
       const seatSelect = document.querySelector('select[name="seat"]');
       if (seatSelect) {
         const availableSeats = @json($bookingData['available_seats'] ?? []);
         console.log('Available seats:', availableSeats);  // Debug info
       }
     });
    </script>

    <!-- Custom CSS -->
    <style>
     /* Gradient Text */
     .gradient-text {
       font-family: "Poppins", sans-serif;
       font-weight: bold;
       letter-spacing: 1px;
       text-transform: uppercase;
       margin: 0;
       padding: 0;
       background: linear-gradient(to bottom, #006064, #26c6da);
       background-clip: text;
       -webkit-background-clip: text;
       color: transparent;
     }

     /* Styled Horizontal Line */
     .styled-hr {
       border: 0;
       height: 2px;
       width: 200px; /* Adjust this value to match the text's width */
       background: linear-gradient(to bottom, #006064, #26c6da);
       margin: 10px auto; /* Center the line and reduce gap */
     }

     /* Optional Animation */
     @keyframes pulse {
       0% {
         transform: scale(1);
         opacity: 1;
       }
       50% {
         transform: scale(1.05);
         opacity: 0.9;
       }
       100% {
         transform: scale(1);
         opacity: 1;
       }
     }
     .gradient-text {
       animation: pulse 1.5s ease-in-out infinite;
     }
    </style>
  </body>
</html>
