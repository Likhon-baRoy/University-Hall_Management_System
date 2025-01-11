@extends('admin.layouts.app')
@section('title', 'Admin Users Panel')
@section('custom-css')
  <style>
   .profile-image-wrapper {
     width: 150px;
     height: 150px;
     margin: 0 auto;
     position: relative;
     overflow: hidden;
     border-radius: 50%;
     border: 5px solid #fff;
     box-shadow: 0 0 15px rgba(0,0,0,0.1);
   }

   .profile-image {
     width: 100%;
     height: 100%;
     object-fit: cover;
   }

   .profile-header {
     border-bottom: 1px solid #eee;
   }

   .bg-soft-primary {
     background-color: rgba(0, 123, 255, 0.1);
   }

   .bg-soft-success {
     background-color: rgba(40, 167, 69, 0.1);
   }

   .info-box {
     height: 100%;
     transition: transform 0.2s;
   }

   .info-box:hover {
     transform: translateY(-2px);
   }

   .detail-item label {
     font-size: 0.875rem;
     margin-bottom: 0.25rem;
     display: block;
   }

   .badge-pill {
     font-size: 0.9rem;
   }

   #userStatus .badge {
     font-size: 0.875rem;
     padding: 0.4em 0.8em;
   }

   .profile-details {
     background-color: #fff;
   }

   .modal-content {
     border: none;
     box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
   }
  </style>
@endsection

@section('main-section')

  <div class="container-fluid">
    <div class="row">
      <div class="col-lg-12">
        <div class="card">
          <div class="card-header d-flex justify-content-between align-items-center">
            <a href="" class="btn btn-primary"><i class="fa fa-user-plus"></i> Add User</a>
            <h4 class="card-title text-center" style="flex-grow: 1; color: #007bff;">All User Database</h4>
            <a href="{{ route('admin.trash') }}" class="btn btn-warning"><i class="fa fa-trash"></i> Trash</a>
          </div>
          <div class="card-body">
            @include('validate-main')
            <div class="table-responsive">
              <table class="table mb-0 data-table-element">

                <thead>
                  <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>Role</th>
                    <th>Cell</th>
                    <th>Gender</th>
                    <th>Dept</th>
                    <th>Semester</th>
                    <th>Photo</th>
                    <th>Status</th>
                    <th>Action</th>
                  </tr>
                </thead>
              <tbody>
                @forelse ($all_user as $user)
                  @if( $user -> name !== 'Provider' )
                    <tr>
                      <td>{{ $loop->iteration }}</td>
                      <td>{{ $user->name }}</td>
                      <td>{{ $user->role->name }}</td>
                      <td>{{ $user->cell }}</td>
                      <td>{{ ucfirst($user->gender) }}</td>
                      <td>{{ $user->dept }}</td>
                      <td>{{ ucfirst($user->semester) }} {{ $user->semester_year }}</td>
                      <td><img src="{{ asset('storage/img/'.$user->photo) }}" width="40"></td>
                      <td>
                        @if($user -> status)
                          <span class="badge badge-success">Active User</span>
                          <a class="text-danger" href="{{ route('admin.status.update', $user -> id) }} "><i class="fa fa-times"></i></a>
                        @else
                          <span class="badge badge-danger">Blocked User</span>
                          <a class="text-success" href="{{ route('admin.status.update', $user -> id) }} "><i class="fa fa-check"></i></a>
                        @endif
                      </td>
                      <td>
                        <a href="#" class="btn btn-sm btn-info view-profile" data-id="{{ $user->id }}"><i class="fa fa-eye"></i></a>
                        <a href="{{ route('admin-user.edit', $user->id) }}" class="btn btn-warning btn-sm"><i class="fa fa-edit"></i></a>
                        <a href="#" class="btn btn-danger btn-sm"><i class="fa fa-trash"></i></a>
                      </td>
                    </tr>
                  @endif
                  @empty
                  <tr>
                    <td class="text-center text-danger" colspan="10">No Records Found</td>
                  </tr>
                @endforelse
              </tbody>

            </table>
          </div>
        </div>
      </div>
    </div>

    <!-- User Profile Modal -->
    <div class="modal fade" id="userProfileModal" tabindex="-1" role="dialog" aria-labelledby="userProfileModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
          <div class="modal-header bg-primary text-white">
            <h5 class="modal-title" id="userProfileModalLabel">
              <i class="fa fa-user-circle"></i> User Profile
            </h5>
            <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body p-0">
            <!-- Profile Header Section -->
            <div class="profile-header bg-light p-4">
              <div class="row align-items-center">
                <div class="col-md-4 text-center">
                  <div class="profile-image-wrapper mb-3">
                    <img id="userPhoto" src="" alt="User Photo" class="profile-image">
                  </div>
                  <h4 id="userName" class="mb-1 font-weight-bold"></h4>
                  <span id="userRole" class="badge badge-primary badge-pill px-3 py-2"></span>
                </div>
                <div class="col-md-8">
                  <div class="quick-info-boxes row">
                    <div class="col-sm-6 mb-3">
                      <div class="info-box p-3 bg-soft-primary rounded">
                        <i class="fa fa-envelope-o text-primary mr-2"></i>
                        <label class="font-weight-bold mb-1">Email</label>
                        <div id="userEmail" class="text-break"></div>
                      </div>
                    </div>
                    <div class="col-sm-6 mb-3">
                      <div class="info-box p-3 bg-soft-success rounded">
                        <i class="fa fa-phone text-success mr-2"></i>
                        <label class="font-weight-bold mb-1">Phone</label>
                        <div id="userCell"></div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <!-- Details Section -->
            <div class="profile-details p-4">
              <h5 class="border-bottom pb-2 mb-4"><i class="fa fa-info-circle"></i> Personal Information</h5>
              <div class="row">
                <div class="col-md-6">
                  <div class="detail-item mb-3">
                    <label class="text-muted">User ID</label>
                    <div id="userId" class="font-weight-bold"></div>
                  </div>
                  <div class="detail-item mb-3">
                    <label class="text-muted">Gender</label>
                    <div id="userGender" class="font-weight-bold"></div>
                  </div>
                  <div class="detail-item mb-3">
                    <label class="text-muted">Department</label>
                    <div id="userDept" class="font-weight-bold"></div>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="detail-item mb-3">
                    <label class="text-muted">Semester</label>
                    <div id="userSemester" class="font-weight-bold"></div>
                  </div>
                  <div class="detail-item mb-3">
                    <label class="text-muted">Hall & Room</label>
                    <div class="font-weight-bold">
                      <span id="userHall"></span> - <span id="userRoom"></span>
                    </div>
                  </div>
                  <div class="detail-item mb-3">
                    <label class="text-muted">Account Status</label>
                    <div id="userStatus"></div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="modal-footer bg-light">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            <a href="#" id="editProfileBtn" class="btn btn-primary">
              <i class="fa fa-edit"></i> Edit Profile
            </a>
          </div>
        </div>
      </div>
    </div>

@endsection

@section('custom-js')
  <script>
   $(document).ready(function() {
     $('.view-profile').click(function(e) {
       e.preventDefault();
       var userId = $(this).data('id');

       // Reset modal content first
       $('.profile-header, .profile-details').hide();
       $('#userProfileModal .modal-body').prepend(
         '<div class="text-center loading-spinner py-5">' +
         '<i class="fa fa-spinner fa-spin fa-3x text-primary"></i>' +
         '<p class="mt-2 text-muted">Loading profile...</p>' +
         '</div>'
       );
       $('#userProfileModal').modal('show');

       // Fetch user data
       $.ajax({
         url: "{{ route('admin-user.show', ':id') }}".replace(':id', userId),
         type: 'GET',
         dataType: 'json',
         success: function(response) {
           // Remove loading spinner
           $('.loading-spinner').remove();
           $('.profile-header, .profile-details').fadeIn();

           // Update modal content with user data
           $('#userPhoto').attr('src', response.photo_url);
           $('#userName').text(response.name);
           $('#userRole').text(response.role.name);
           $('#userId').text(response.user_id || 'N/A');
           $('#userEmail').text(response.email);
           $('#userCell').text(response.cell);
           $('#userGender').text(response.gender ?
                                 (response.gender.charAt(0).toUpperCase() + response.gender.slice(1)) : 'N/A');
           $('#userDept').text(response.dept || 'N/A');
           $('#userSemester').text(
             (response.semester ? response.semester + ' ' : '') +
                     (response.semester_year || 'N/A')
           );
           $('#userHall').text(response.hall || 'N/A');
           $('#userRoom').text(response.room || 'N/A');
           $('#userStatus').html(response.status ?
                                 '<span class="badge badge-success"><i class="fa fa-check-circle"></i> Active</span>' :
                                 '<span class="badge badge-danger"><i class="fa fa-ban"></i> Blocked</span>'
           );

           // Update edit profile button href
           $('#editProfileBtn').attr('href', "{{ route('admin-user.edit', ':id') }}".replace(':id', userId));
         },
         error: function(xhr) {
           // Remove loading spinner
           $('.loading-spinner').remove();

           // Show error message
           $('#userProfileModal .modal-body').html(
             '<div class="alert alert-danger m-4">' +
             '<i class="fa fa-exclamation-triangle mr-2"></i>' +
             'Error loading user data. Status: ' + xhr.status +
             '<br>Message: ' + (xhr.responseJSON?.message || 'Unknown error') +
             '</div>'
           );

           console.error('Ajax error:', xhr);
         }
       });
     });
   });
  </script>
@endsection
