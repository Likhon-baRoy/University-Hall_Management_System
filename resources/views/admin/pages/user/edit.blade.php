@extends('admin.layouts.app')
@section('title', 'Edit User')

@section('main-section')
  <div class="page-header">
    <div class="row align-items-center">
      <div class="col">
        <h3 class="page-title">Edit User</h3>
        <ul class="breadcrumb">
          <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
          <li class="breadcrumb-item active">Edit</li>
        </ul>
        <br />
        <a href="{{ route('admin-user.index') }}" class="btn btn-secondary">Back to Users</a>
      </div>
    </div>
  </div>

  <div class="row">
    <div class="col-lg-8">
      <div class="card">
        <div class="card-body">
          @include('validate')
          <form action="{{ route('admin-user.update', $user->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="form-group">
              <label>Name</label>
              <input name="name" type="text" value="{{ $user->name }}" class="form-control">
            </div>
            <div class="form-group">
              <label>User ID</label>
              <input name="user_id" type="text" value="{{ $user->user_id }}" class="form-control" readonly>
            </div>
            <div class="form-group">
              <label>Username</label>
              <input name="username" type="text" value="{{ $user->username }}" class="form-control">
            </div>
            <div class="form-group">
              <label>Email</label>
              <input name="email" type="email" value="{{ $user->email }}" class="form-control">
            </div>
            <div class="form-group">
              <label>Cell</label>
              <input name="cell" type="text" value="{{ $user->cell }}" class="form-control">
            </div>
            <div class="form-group">
              <label>Gender</label>
              <select name="gender" class="form-control">
                @foreach(['male', 'female', 'other'] as $gender)
                  <option value="{{ $gender }}" {{ $user->gender == $gender ? 'selected' : '' }}>
                    {{ ucfirst($gender) }}
                  </option>
                @endforeach
              </select>
            </div>
            <div class="form-group">
              <label>Role</label>
              <select name="role_id" class="form-control">
                @foreach ($roles as $role)
                  <option value="{{ $role->id }}" {{ $user->role_id == $role->id ? 'selected' : '' }}>
                    {{ $role->name }}
                  </option>
                @endforeach
              </select>
            </div>
            <div class="form-group">
              <label>Photo</label>
              <div class="mb-3">
                <img id="make-photo-preview"
                     src="{{ url('storage/image/profile/' . ($user->photo ?? 'avatar.png')) }}"
                     alt="Profile Picture"
                     style="max-width: 100%; max-height: 200px; object-fit: cover;">
              </div>
              <input type="file"
                     name="photo"
                     class="form-control"
                     id="photo-preview"
                     accept="image/jpeg,image/png,image/jpg,image/webp">
              <small class="text-muted">Leave empty to keep current photo</small>
            </div>
            <div class="text-right">
              <button type="submit" class="btn btn-primary">Update</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
@endsection
