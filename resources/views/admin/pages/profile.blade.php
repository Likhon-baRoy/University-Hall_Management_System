@extends('admin.layouts.app')

@section('main-section')

  <div class="row">
	<div class="col-md-12">
	  <div class="profile-header">
		<div class="row align-items-center">
		  <div class="col-auto profile-image">
			<a href="#">
			  <img class="rounded-circle" alt="User Image" src="{{ url('storage/img/' . Auth::guard('admin') -> user() -> photo) }}">
			</a>
		  </div>
		  <div class="col ml-md-n2 profile-user-info">
			<h4 class="user-name mb-0">{{ Auth::guard('admin') -> user() -> name }}</h4>
			<h6 class="text-muted">{{ Auth::guard('admin') -> user() -> email }}</h6>
			<div class="user-Location"><i class="fa fa-map-marker"></i> {{ Auth::guard('admin') -> user() -> address }}</div>
			<div class="about-text">{{ Auth::guard('admin') -> user() -> bio }}</div>
		  </div>

		  <div class="col-auto profile-btn">
            <a class="btn btn-primary" data-toggle="modal" href="#edit_personal_details"> Edit </a>
		  </div>
		</div>
	  </div>
	  <div class="profile-menu">
		<ul class="nav nav-tabs nav-tabs-solid">
		  <li class="nav-item">
			<a class="nav-link active" data-toggle="tab" href="#per_details_tab">About</a>
		  </li>
		  <li class="nav-item">
			<a class="nav-link" data-toggle="tab" href="#password_tab">Password</a>
		  </li>
		</ul>
	  </div>

	  <div class="tab-content profile-tab-cont">
        <!-- Personal Details Tab -->
        <div class="tab-pane fade show active" id="per_details_tab">
          <!-- Personal Details -->
          <div class="row">
            <div class="col-lg-12">
              <div class="card">
                <div class="card-body">
                  <h5 class="card-title d-flex justify-content-between">
                    <span>Personal Details</span>
                    <a class="edit-link" data-toggle="modal" href="#edit_personal_details"><i class="fa fa-edit mr-1"></i>Edit</a>
                  </h5>

                  @php
                  // Define an array of fields to loop through
                  $fields = [
                  'Name' => Auth::guard('admin')->user()->name,
                  'Date of Birth' => Auth::guard('admin')->user()->dob,
                  'Email ID' => Auth::guard('admin')->user()->email,
                  'Mobile' => Auth::guard('admin')->user()->cell,
                  'Address' => Auth::guard('admin')->user()->address,
                  ];
                  @endphp

                  @foreach ($fields as $label => $value)
                    <div class="row">
                      <p class="col-sm-2 text-muted text-sm-right mb-0 mb-sm-3">{{ $label }}</p>
                      <p class="col-sm-10">{{ $value }}</p>
                    </div>
                  @endforeach

                </div>
              </div>
              <!-- /Personal Details -->

              <!-- Edit Details Modal -->
              <div class="modal fade" id="edit_personal_details" aria-hidden="true" role="dialog">
                <div class="modal-dialog modal-dialog-centered" role="document">
                  <div class="modal-content">
                    <div class="modal-header">
                      <h5 class="modal-title">Personal Details</h5>
                      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                      </button>
                    </div>
                    <div class="modal-body">

                      <form method="POST" action="">
                        @csrf
                        @method('PUT')

                        <div class="row form-row">
                          <!-- First Name -->
                          <div class="col-12 col-sm-6">
                            <div class="form-group">
                              <label>First Name</label>
                              <input type="text" class="form-control" name="first_name" value="{{ old('first_name', Auth::guard('admin')->user()->first_name) }}">
                            </div>
                          </div>

                          <!-- Last Name -->
                          <div class="col-12 col-sm-6">
                            <div class="form-group">
                              <label>Last Name</label>
                              <input type="text" class="form-control" name="last_name" value="{{ old('last_name', Auth::guard('admin')->user()->last_name) }}">
                            </div>
                          </div>

                          <!-- Date of Birth -->
                          <div class="col-12">
                            <div class="form-group">
                              <label>Date of Birth</label>
                              <div class="cal-icon">
                                <input type="text" class="form-control" name="date_of_birth" value="{{ old('date_of_birth', Auth::guard('admin')->user()->date_of_birth) }}">
                              </div>
                            </div>
                          </div>

                          <!-- Email -->
                          <div class="col-12 col-sm-6">
                            <div class="form-group">
                              <label>Email ID</label>
                              <input type="email" class="form-control" name="email" value="{{ old('email', Auth::guard('admin')->user()->email) }}">
                            </div>
                          </div>

                          <!-- Mobile -->
                          <div class="col-12 col-sm-6">
                            <div class="form-group">
                              <label>Mobile</label>
                              <input type="text" class="form-control" name="cell" value="{{ old('cell', Auth::guard('admin')->user()->cell) }}">
                            </div>
                          </div>

                          <!-- Address -->
                          <div class="col-12">
                            <h5 class="form-title"><span>Address</span></h5>
                          </div>

                          <!-- Address Field -->
                          <div class="col-12">
                            <div class="form-group">
                              <label>Address</label>
                              <input type="text" class="form-control" name="address" value="{{ old('address', Auth::guard('admin')->user()->address) }}">
                            </div>
                          </div>

                          <!-- City -->
                          <div class="col-12 col-sm-6">
                            <div class="form-group">
                              <label>City</label>
                              <input type="text" class="form-control" name="city" value="{{ old('city', Auth::guard('admin')->user()->city) }}">
                            </div>
                          </div>

                          <!-- State -->
                          <div class="col-12 col-sm-6">
                            <div class="form-group">
                              <label>State</label>
                              <input type="text" class="form-control" name="state" value="{{ old('state', Auth::guard('admin')->user()->state) }}">
                            </div>
                          </div>

                          <!-- Zip Code -->
                          <div class="col-12 col-sm-6">
                            <div class="form-group">
                              <label>Zip Code</label>
                              <input type="text" class="form-control" name="zip_code" value="{{ old('zip_code', Auth::guard('admin')->user()->zip_code) }}">
                            </div>
                          </div>

                          <!-- Country -->
                          <div class="col-12 col-sm-6">
                            <div class="form-group">
                              <label>Country</label>
                              <input type="text" class="form-control" name="country" value="{{ old('country', Auth::guard('admin')->user()->country) }}">
                            </div>
                          </div>
                        </div>

                        <button type="submit" class="btn btn-primary btn-block">Save Changes</button>
                      </form>
                    </div>
                  </div>
                </div>
              </div>
              <!-- /Edit Details Modal -->


			</div>


		  </div>
		  <!-- /Personal Details -->

		</div>
		<!-- /Personal Details Tab -->

		<!-- Change Password Tab -->
		<div id="password_tab" class="tab-pane fade">

		  <div class="card">
			<div class="card-body">
			  <h5 class="card-title">Change Password</h5>
			  <div class="row">
				<div class="col-md-10 col-lg-6">
				  <form>
					<div class="form-group">
					  <label>Old Password</label>
					  <input type="password" class="form-control">
					</div>
					<div class="form-group">
					  <label>New Password</label>
					  <input type="password" class="form-control">
					</div>
					<div class="form-group">
					  <label>Confirm Password</label>
					  <input type="password" class="form-control">
					</div>
					<button class="btn btn-primary" type="submit">Save Changes</button>
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

@endsection
