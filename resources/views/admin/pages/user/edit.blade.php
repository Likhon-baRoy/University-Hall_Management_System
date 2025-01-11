@extends('admin.layouts.app')
@section('title', 'Edit User')

@section('main-section')
  <div class="card">
    <div class="card-header">
      <h4 class="card-title">Edit User</h4>
      <a href="{{ route('admin-user.index') }}" class="btn btn-secondary btn-sm">Back to Users</a>
    </div>
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
          <label>User Type</label>
          <select name="user_type" class="form-control">
            @foreach(['student', 'teacher', 'staff', 'admin', 'sadmin', 'editor', 'author'] as $type)
              <option value="{{ $type }}" {{ $user->user_type == $type ? 'selected' : '' }}>
                {{ ucfirst($type) }}
              </option>
            @endforeach
          </select>
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
          <input name="photo" type="file" class="form-control">
          <img src="{{ asset('storage/img/'.$user->photo) }}" width="60" class="mt-2">
        </div>
        <div class="text-right">
          <button type="submit" class="btn btn-primary">Update</button>
        </div>
      </form>
    </div>
  </div>
@endsection
