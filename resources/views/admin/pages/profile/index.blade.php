@extends('admin.layouts.app')
@section('title', 'Profile Management')
@section('custom-css')
  <link rel="stylesheet" href="{{ asset('admin/assets/css/user-profile.css') }}">
@endsection

@section('main-section')
  <div class="container-fluid">
    @include('validate-main')
    <div class="row">
      <div class="col-md-12">
        <div class="profile-header">
          <div class="row align-items-center">
            <div class="col-auto profile-photo-container">
              <img class="rounded-circle" alt="User Image"
                   src="{{ url('storage/image/profile/' . ($user->photo ?? 'avatar.png')) }}"
                   style="max-width: 100%; max-height: 200px; object-fit: cover;">
            </div>
            <div class="col ml-md-n2 profile-user-info">
              <h4 class="user-name mb-0">{{ $user->name }}</h4>
              <h6 class="text-muted">{{ $user->email }}</h6>
              <div class="user-Location"><i class="fa-solid fa-location-dot"></i> {{ $user->address ?: 'No address provided' }}</div>
              <div class="about-text">{{ $user->bio ?: 'No bio available' }}</div>
            </div>
            <div class="col-auto profile-btn">
              <a href="#" class="btn btn-primary" data-toggle="modal" data-target="#edit_details">
                Edit
              </a>
            </div>
          </div>
        </div>

        <div class="profile-menu">

          <!-- Determine active tab based on query parameter -->
          @php
          $activeTab = request('tab', 'about'); // Prioritize URL parameter
          @endphp

          <ul class="nav nav-tabs nav-tabs-solid">
            <li class="nav-item">
              <a class="nav-link {{ $activeTab == 'about' ? 'active' : '' }}"
                 href="{{ route('profile.index', ['tab' => 'about']) }}">About</a>
            </li>
            <li class="nav-item">
              <a class="nav-link {{ $activeTab == 'password' ? 'active' : '' }}"
                 href="{{ route('profile.index', ['tab' => 'password']) }}">Password</a>
            </li>
          </ul>
        </div>

        <div class="tab-content profile-tab-cont">
          <!-- Personal Details Tab -->
          <div class="tab-pane fade {{ $activeTab == 'about' ? 'show active' : '' }}" id="per_details_tab">
            <div class="row">
              <div class="col-lg-6">
                <div class="card">
                  <div class="card-body">
                    <h5 class="card-title d-flex justify-content-between">
                      <span>Basic Information</span>
                    </h5><hr>
                    <div class="detail-section">
                      <div class="row mb-3">
                        <p class="col-sm-4 text-muted mb-0">Full Name</p>
                        <p class="col-sm-8">: {{ $user->name }}</p>
                      </div>
                      <div class="row mb-3">
                        <p class="col-sm-4 text-muted mb-0">Email</p>
                        <p class="col-sm-8">: {{ $user->email }}</p>
                      </div>
                      <div class="row mb-3">
                        <p class="col-sm-4 text-muted mb-0">Phone</p>
                        <p class="col-sm-8">: {{ $user->cell }}</p>
                      </div>
                      <div class="row mb-3">
                        <p class="col-sm-4 text-muted mb-0">Date of Birth</p>
                        <p class="col-sm-8">: {{ $user->dob ?: 'Not provided' }}</p>
                      </div>
                      <div class="row">
                        <p class="col-sm-4 text-muted mb-0">Gender</p>
                        <p class="col-sm-8">: {{ ucfirst($user->gender) }}</p>
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <div class="col-lg-6">
                <div class="card">
                  <div class="card-body">
                    <h5 class="card-title d-flex justify-content-between">
                      <span>Academic Information</span>
                    </h5><hr>
                    <div class="detail-section">
                      <div class="row mb-3">
                        <p class="col-sm-4 text-muted mb-0">User Type</p>
                        <p class="col-sm-8">: {{ ucfirst($user->user_type) }}</p>
                      </div>
                      <div class="row mb-3">
                        <p class="col-sm-4 text-muted mb-0">Department</p>
                        <p class="col-sm-8">: {{ $user->dept ?: 'Not assigned' }}</p>
                      </div>
                      <div class="row mb-3">
                        <p class="col-sm-4 text-muted mb-0">Hall</p>
                        <p class="col-sm-8">: {{ $user->hall }}</p>
                      </div>
                      <div class="row mb-3">
                        <p class="col-sm-4 text-muted mb-0">Room</p>
                        <p class="col-sm-8">: {{ $user->room }}</p>
                      </div>
                      <div class="row">
                        <p class="col-sm-4 text-muted mb-0">Seat</p>
                        <p class="col-sm-8">: {{ $user->seat ?: 'Not assigned' }}</p>
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <!-- Bio Card -->
              <div class="col-12 mt-4">
                <div class="card">
                  <div class="card-body">
                    <h5 class="card-title">Bio</h5>
                    <p class="bio-text">{{ $user->bio ?: 'No bio available' }}</p>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <!-- /Personal Details Tab -->

          <!-- Change Password Tab -->
          <div class="tab-pane fade {{ $activeTab == 'password' ? 'show active' : '' }}" id="password_tab">
            <div class="card">
              <div class="card-body">
                <h5 class="card-title">Change Password</h5>
                <div class="row">
                  <div class="col-md-10 col-lg-6">
                    @include('validate')
                    <form action="{{ route('profile.update-password') }}" method="POST">
                      @csrf
                      <input type="hidden" name="tab" value="password">

                      <div class="form-group">
                        <label>Old Password</label>
                        <input name="old_pass" type="password" class="form-control @error('old_pass') is-invalid @enderror">
                        @error('old_pass')
                        <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                      </div>
                      <div class="form-group">
                        <label>New Password</label>
                        <input name="pass" type="password" class="form-control @error('pass') is-invalid @enderror">
                        @error('pass')
                        <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                      </div>
                      <div class="form-group">
                        <label>Confirm Password</label>
                        <input name="pass_confirmation" type="password" class="form-control">
                      </div>
                      <button class="btn btn-primary" type="submit">Update Password</button>
                    </form>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <!-- /Change Password Tab -->
        </div>
      </div>
    </div>
  </div>
  @include('admin.pages.profile.edit')
@endsection

@section('custom-js')
  <script src="{{ asset('custom/profile-edit-modal.js') }}"></script>
@endsection
