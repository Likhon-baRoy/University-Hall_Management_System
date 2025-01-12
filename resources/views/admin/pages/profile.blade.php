@extends('admin.layouts.app')
@section('title', 'Profile Management')

@section('main-section')
  <div class="container-fluid">
    @if(session('success'))
      <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
    @endif

    @if(session('error'))
      <div class="alert alert-danger alert-dismissible fade show" role="alert">
        {{ session('error') }}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
    @endif

    <div class="row">
      <div class="col-md-12">
        <div class="profile-header">
          <div class="row align-items-center">
            <div class="col-auto profile-image">
              <a href="#">
                <img class="rounded-circle" alt="User Image" src="{{ asset('storage/img/' . $user->photo) }}" style="width: 100px; height: 100px; object-fit: cover;">
              </a>
            </div>
            <div class="col ml-md-n2 profile-user-info">
              <h4 class="user-name mb-0">{{ $user->name }}</h4>
              <h6 class="text-muted">{{ $user->email }}</h6>
              <div class="user-Location"><i class="fa fa-map-marker"></i> {{ $user->address ?: 'No address provided' }}</div>
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
            <div class="row">
              <div class="col-lg-6">
                <div class="card">
                  <div class="card-body">
                    <h5 class="card-title d-flex justify-content-between">
                      <span>Basic Information</span>
                    </h5>
                    <div class="detail-section">
                      <div class="row mb-3">
                        <p class="col-sm-4 text-muted mb-0">Full Name</p>
                        <p class="col-sm-8">{{ $user->name }}</p>
                      </div>
                      <div class="row mb-3">
                        <p class="col-sm-4 text-muted mb-0">Email</p>
                        <p class="col-sm-8">{{ $user->email }}</p>
                      </div>
                      <div class="row mb-3">
                        <p class="col-sm-4 text-muted mb-0">Phone</p>
                        <p class="col-sm-8">{{ $user->cell }}</p>
                      </div>
                      <div class="row mb-3">
                        <p class="col-sm-4 text-muted mb-0">Date of Birth</p>
                        <p class="col-sm-8">{{ $user->dob ?: 'Not provided' }}</p>
                      </div>
                      <div class="row">
                        <p class="col-sm-4 text-muted mb-0">Gender</p>
                        <p class="col-sm-8">{{ ucfirst($user->gender) }}</p>
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
                    </h5>
                    <div class="detail-section">
                      <div class="row mb-3">
                        <p class="col-sm-4 text-muted mb-0">User Type</p>
                        <p class="col-sm-8">{{ ucfirst($user->user_type) }}</p>
                      </div>
                      <div class="row mb-3">
                        <p class="col-sm-4 text-muted mb-0">Department</p>
                        <p class="col-sm-8">{{ $user->dept ?: 'Not assigned' }}</p>
                      </div>
                      <div class="row mb-3">
                        <p class="col-sm-4 text-muted mb-0">Hall</p>
                        <p class="col-sm-8">{{ $user->hall }}</p>
                      </div>
                      <div class="row mb-3">
                        <p class="col-sm-4 text-muted mb-0">Room</p>
                        <p class="col-sm-8">{{ $user->room }}</p>
                      </div>
                      <div class="row">
                        <p class="col-sm-4 text-muted mb-0">Seat</p>
                        <p class="col-sm-8">{{ $user->seat ?: 'Not assigned' }}</p>
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
          <div class="tab-pane fade" id="password_tab">
            <div class="card">
              <div class="card-body">
                <h5 class="card-title">Change Password</h5>
                <div class="row">
                  <div class="col-md-10 col-lg-6">
                    <form action="{{ route('profile.update-password') }}" method="POST">
                      @csrf
                      @method('PUT')
                      <div class="form-group">
                        <label>Old Password</label>
                        <input type="password" class="form-control @error('current_password') is-invalid @enderror" name="current_password">
                        @error('current_password')
                        <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                      </div>
                      <div class="form-group">
                        <label>New Password</label>
                        <input type="password" class="form-control @error('password') is-invalid @enderror" name="password">
                        @error('password')
                        <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                      </div>
                      <div class="form-group">
                        <label>Confirm Password</label>
                        <input type="password" class="form-control" name="password_confirmation">
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
  </div>

  <!-- Streamlined Edit Modal -->
  <div class="modal fade edit-modal" id="edit_details" aria-hidden="true" role="dialog">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Edit Profile</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <form action="{{ route('profile.update', $user->id) }}" method="POST" enctype="multipart/form-data" class="needs-validation" novalidate>
            @csrf
            @method('PUT')

            <!-- Profile Photo Section -->
            <div class="text-center mb-4">
              <div class="profile-photo-container">
                <img src="{{ asset('storage/img/' . $user->photo) }}" alt="Profile Photo" class="rounded-circle profile-photo" style="width: 120px; height: 120px; object-fit: cover;">
                <div class="photo-upload-overlay">
                  <label for="photo" class="mb-0">
                    <i class="fa fa-camera"></i>
                  </label>
                  <input type="file" id="photo" name="photo" class="d-none" accept="image/*">
                </div>
              </div>
              <small class="text-muted">Click to change profile photo</small>
            </div>

            <div class="row">
              <div class="col-md-6">
                <div class="form-group">
                  <label>Full Name</label>
                  <input type="text" class="form-control" name="name" value="{{ $user->name }}" required>
                </div>

                <div class="form-group">
                  <label>Phone Number</label>
                  <input type="text" class="form-control" name="cell" value="{{ $user->cell }}" required>
                </div>
              </div>

              <div class="col-md-6">
                <div class="form-group">
                  <label>Date of Birth</label>
                  <input type="date" class="form-control" name="dob" value="{{ $user->dob }}">
                </div>

                <div class="form-group">
                  <label>Address</label>
                  <input type="text" class="form-control" name="address" value="{{ $user->address }}">
                </div>
              </div>
            </div>

            <div class="form-group">
              <label>Bio</label>
              <textarea class="form-control" name="bio" rows="4">{{ $user->bio }}</textarea>
              <small class="text-muted">Brief description for your profile.</small>
            </div>

            <div class="text-right mt-3">
              <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
              <button type="submit" class="btn btn-primary">Save Changes</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
  <!-- /Streamlined Edit Modal -->

  <!-- Update Photo Modal -->
  <div class="modal fade" id="updatePhotoModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Update Profile Photo</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <form action="{{ route('profile.update-photo') }}" method="POST" enctype="multipart/form-data">
          @csrf
          @method('PUT')
          <div class="modal-body">
            <div class="form-group">
              <label>Choose Photo</label>
              <div class="custom-file">
                <input type="file" class="custom-file-input" id="photo" name="photo" accept="image/*">
                <label class="custom-file-label" for="photo">Select file</label>
              </div>
              <div id="photoPreview" class="mt-3 text-center" style="display: none;">
                <img src="" alt="Preview" style="max-width: 200px; max-height: 200px;">
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
            <button type="submit" class="btn btn-primary">Upload Photo</button>
          </div>
        </form>
      </div>
    </div>
  </div>
@endsection

@section('custom-css')
  <style>
   .detail-section {
     padding: 1.25rem 0;
     border-bottom: 1px solid #eee;
   }

   .detail-section:last-child {
     border-bottom: none;
     padding-bottom: 0;
   }

   .section-title {
     color: #333;
     font-size: 0.95rem;
     font-weight: 600;
     margin-bottom: 1rem;
   }

   .bio-text {
     color: #666;
     line-height: 1.6;
     margin: 0;
   }

   /* Edit Modal Styles */
   .profile-photo-container {
     position: relative;
     display: inline-block;
     margin-bottom: 10px;
   }

   .photo-upload-overlay {
     position: absolute;
     bottom: 0;
     right: 0;
     background: rgba(0, 0, 0, 0.5);
     width: 32px;
     height: 32px;
     border-radius: 50%;
     display: flex;
     align-items: center;
     justify-content: center;
     cursor: pointer;
   }

   .photo-upload-overlay i {
     color: white;
   }

   .photo-upload-overlay:hover {
     background: rgba(0, 0, 0, 0.7);
   }

   .detail-section {
     margin-bottom: 0;
     border-bottom: none;
   }

   .edit-modal .modal-dialog {
     max-width: 400px;
   }

   .edit-modal .modal-content {
     border: none;
     border-radius: 8px;
     box-shadow: 0 2px 20px rgba(0,0,0,0.1);
   }

   .edit-modal .modal-header {
     padding: 1rem 1.5rem;
     border-bottom: 1px solid #eee;
   }

   .edit-modal .modal-body {
     padding: 1.5rem;
   }

   .edit-modal .form-group {
     margin-bottom: 1rem;
   }

   .edit-modal .form-group label {
     font-weight: 500;
     font-size: 0.875rem;
     color: #444;
     margin-bottom: 0.375rem;
   }

   .edit-modal .form-control {
     font-size: 0.875rem;
     padding: 0.5rem 0.75rem;
     border: 1px solid #ddd;
     border-radius: 4px;
     transition: all 0.2s;
   }

   .edit-modal .form-control:focus {
     border-color: #80bdff;
     box-shadow: 0 0 0 0.2rem rgba(0,123,255,.25);
   }

   .edit-modal .btn {
     padding: 0.375rem 1rem;
     font-size: 0.875rem;
   }

   .edit-modal .btn-light {
     background: #f8f9fa;
     border: 1px solid #ddd;
   }

   .edit-modal .btn-light:hover {
     background: #e9ecef;
   }
  </style>
@endsection

@section('custom-js')
  <script>
   $(document).ready(function() {
     // Enable modal dismissal with Escape key
     $(document).keydown(function(e) {
       if (e.key === "Escape") {
         $('.modal').modal('hide');
       }
     });

     // Update custom file input label
     $('.custom-file-input').on('change', function() {
       let fileName = $(this).val().split('\\').pop();
       $(this).next('.custom-file-label').html(fileName);

       // Preview image
       if (this.files && this.files[0]) {
         let reader = new FileReader();
         reader.onload = function(e) {
           $('#photoPreview img').attr('src', e.target.result);
           $('#photoPreview').show();
         }
         reader.readAsDataURL(this.files[0]);
       }
     });

     // Close photo modal when edit modal is opened
     $('#edit_details').on('show.bs.modal', function() {
       $('#updatePhotoModal').modal('hide');
     });

     // Reset photo preview when modal is closed
     $('#updatePhotoModal').on('hidden.bs.modal', function() {
       $('#photoPreview').hide();
       $('.custom-file-label').html('Select file');
       $('.custom-file-input').val('');
     });

     // Form validation
     $('form').on('submit', function(e) {
       let requiredFields = $(this).find('[required]');
       let valid = true;

       requiredFields.each(function() {
         if (!$(this).val()) {
           valid = false;
           $(this).addClass('is-invalid');
         } else {
           $(this).removeClass('is-invalid');
         }
       });

       if (!valid) {
         e.preventDefault();
         alert('Please fill in all required fields');
       }
     });

     // Password match validation
     $('input[name="password"], input[name="password_confirmation"]').on('keyup', function() {
       let password = $('input[name="password"]').val();
       let confirm = $('input[name="password_confirmation"]').val();

       if (password && confirm) {
         if (password !== confirm) {
           $('input[name="password_confirmation"]').addClass('is-invalid');
           $('.password-match-feedback').remove();
           $('input[name="password_confirmation"]').after(
             '<div class="invalid-feedback password-match-feedback">Passwords do not match</div>'
           );
         } else {
           $('input[name="password_confirmation"]').removeClass('is-invalid');
           $('.password-match-feedback').remove();
         }
       }
     });

     // Add required fields validation
     $('#edit_details form').find('input[name="name"], input[name="email"], input[name="cell"]').prop('required', true);

     // Initialize date picker for DOB field if needed
     if ($.fn.datepicker) {
       $('input[name="dob"]').datepicker({
         format: 'yyyy-mm-dd',
         autoclose: true,
         todayHighlight: true,
         endDate: new Date()
       });
     }

     // Character counter for bio
     $('textarea[name="bio"]').on('keyup', function() {
       let maxLength = 1000;
       let length = $(this).val().length;
       let remaining = maxLength - length;

       if (!$(this).next('.char-count').length) {
         $(this).after('<small class="char-count text-muted"></small>');
       }

       $(this).next('.char-count').text(`${remaining} characters remaining`);

       if (remaining < 0) {
         $(this).addClass('is-invalid');
         $(this).next('.char-count').removeClass('text-muted').addClass('text-danger');
       } else {
         $(this).removeClass('is-invalid');
         $(this).next('.char-count').removeClass('text-danger').addClass('text-muted');
       }
     });

     // Phone number formatting
     $('input[name="cell"]').on('input', function() {
       let number = $(this).val().replace(/[^\d]/g, '');
       if (number.length >= 10) {
         number = number.substring(0, 10);
         number = number.replace(/(\d{3})(\d{3})(\d{4})/, '$1-$2-$3');
       }
       $(this).val(number);
     });

     // Show confirmation before canceling changes
     $('.modal .btn-secondary').on('click', function(e) {
       let formChanged = false;
       $(this).closest('.modal').find('input, textarea').each(function() {
         if ($(this).val() !== $(this).prop('defaultValue')) {
           formChanged = true;
           return false;
         }
       });

       if (formChanged) {
         if (!confirm('Are you sure you want to discard your changes?')) {
           e.preventDefault();
         }
       }
     });
   });
  </script>
@endsection
