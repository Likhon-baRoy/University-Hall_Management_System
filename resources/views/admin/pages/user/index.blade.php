@extends('admin.layouts.app')

@section('main-section')

  <div class="row">
    <div class="col-lg-8">
      <div class="card">
        <div class="card-header">
          <h4 class="card-title">All Admin User</h4>
          @include('validate-main')
        </div>
        <div class="card-body">
          <div class="table-responsive">
            <table class="table mb-0">
              <thead>
                <tr>
                  <th>#</th>
                  <th>Name</th>
                  <th>Role</th>
                  <th>Photo</th>
                  <th>Created At</th>
                  <th>Status</th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody>

                @forelse ($all_admin as $item)

                  @if( $item -> name !== 'Provider' )
                    <tr>
                      <td>{{$loop -> index + 1}}</td>
                      <td>{{$item -> name}}</td>
                      <td>{{$item -> role_id}}</td>
                      <td>
                        @if ($item -> photo == 'avatar.png')
                          <img style="width: 60px; height:60px; object-fit:cover" src="{{ url('storage/avatar.png') }}" alt="">
                        @endif
                      </td>
                      <td>{{$item -> created_at -> diffForHumans()}}</td>
                      <td>
                        @if($item -> status)
                          <span class="badge badge-success">Active User</span>
                          <a class="text-danger" href="{{ route('admin.status.update', $item -> id) }} "><i class="fa fa-times"></i></a>
                        @else
                          <span class="badge badge-danger">Blocked User</span>
                          <a class="text-success" href="{{ route('admin.status.update', $item -> id) }} "><i class="fa fa-check"></i></a>
                        @endif
                      </td>
                      <td>
                        <a class="btn btn-sm btn-info" href="#"><i class="fa fa-eye"></i></a>
                        <a class="btn btn-sm btn-warning" href="{{ route('admin-user.edit', $item -> id) }} "><i class="fa fa-edit"></i></a>

                        <form method="POST" action="{{ route('admin-user.destroy', $item -> id) }}" class="d-inline delete-form">
                          @csrf
                          @method('DELETE')
                          <button class="btn btn-sm btn-danger" type="submit"><i class="fa fa-trash"></i></button>
                        </form>
                      </td>
                    </tr>
                  @endif

                @empty
                  <tr>
                    <td class="text-center text-danger" colspan="5">No Records Found</td>
                  </tr>
                @endforelse

              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
    <div class="col-md-4">

            @if ($form_type == 'create')
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Add New User</h4>
                    </div>
                    <div class="card-body">
                     @include('validate')
                        <form action="{{route('admin-user.store')}}" method="POST">
                            @csrf
                            <div class="form-group">
                                <label>Name</label>
                                <input name="name" type="text" class="form-control">
                            </div>

                            <div class="form-group">
                              <label>Email</label>
                              <input name="email" type="text" class="form-control">
                            </div>

                            <div class="form-group">
                              <label>Cell</label>
                              <input name="cell" type="text" class="form-control">
                            </div>

                            <div class="form-group">
                              <label>Username</label>
                              <input name="username" type="text" class="form-control">
                            </div>

                            <div class="form-group">
                              <label>Role</label>
                              <select name="role" id="" class="form-control">
                                    <option value="">-- Select --</option>
                                 @foreach ($roles as $role)
                                     <option value="">{{$role -> name}}</option>
                                 @endforeach
                              </select>
                            </div>

                            <div class="text-right">
                              <button type="submit" class="btn btn-primary">Submit</button>
                            </div>
                        </form>
                    </div>
                </div>
            @endif

            {{-- @if ( $form_type == 'edit')
            <div class="card">
            <div class="card-header d-flex justify-content-between">
            <h4 class="card-title">Edit Admin</h4>
            <a href="{{ route('admin-user.index') }}">Go Back</a>
            </div>
            <div class="card-body">
            <form action="{{ route('admin-user.update', $edit -> id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="form-group">
            @include('validate')
            <label>Name</label>
            <input name="name" type="text" value="{{ $edit -> name }}" class="form-control">
            </div>
            <div class="text-right">
            <button type="submit" class="btn btn-primary">Submit</button>
            </div>
            </form>
            </div>
            </div>
            @endif --}}

    </div>
  </div>

@endsection
