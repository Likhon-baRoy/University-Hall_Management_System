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
            <h4 class="card-title text-center" style="flex-grow: 1; color: #007bff;">Hall Residents</h4>
            <a href="{{ route('admin-user.trash') }}" class="btn btn-warning"><i class="fa-solid fa-trash-can"></i> Trash</a>
          </div>
          <div class="card-body">
            @include('validate-main')
            <div class="table-responsive">
              <table class="table mb-0 data-table-element">

                <thead>
                  <tr>
                    <th>User ID</th>
                    <th>Name</th>
                    <th>User Type</th>
                    <th>Dept</th>
                    <th>Hall Name</th>
                    <th>Room</th>
                    <th>Photo</th>
                    <th>Status</th>
                    <th>Action</th>
                  </tr>
                </thead>
                <tbody>
                  @forelse ($users as $user)
                    @if($user->role->slug !== 'super-admin')
                      <tr>
                        <td>{{ $user->user_id }}</td>
                        <td>{{ $user->name }}</td>
                        <td>
                          <span class="badge badge-{{ $user->role->name === 'Student' ? 'info' :
                                                      ($user->role->name === 'Teacher' ? 'warning' :
                                                        ($user->role->name === 'Staff' ? 'success' : 'secondary')) }}">
                            {{ $user->role->name ?? 'N/A' }}
                          </span>
                        </td>
                        <td>{{ $user->dept }}</td>
                        <td>{{ $user->hall }}</td>
                        <td>{{ $user->room }}</td>
                        <td><img src="{{ url('storage/image/profile/' . ($user->photo ?? 'avatar.png')) }}" width="40"></td>
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

                          <form action="{{ route('admin-user.destroy', $user->id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger">
                              <i class="fa-solid fa-trash-can"></i>
                            </button>
                          </form>

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
    </div>
  </div>
  @include('admin.pages.user.view')
@endsection
