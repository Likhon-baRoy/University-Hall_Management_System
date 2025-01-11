@extends('admin.layouts.app')
@section('title', 'Add New User')

@section('main-section')
  <!-- Start -->
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
          <input name="name" type="text" value="{{ old('name') }}" class="form-control">
        </div>

        <div class="form-group">
          <label>Email</label>
          <input name="email" type="text" value="{{ old('email') }}" class="form-control">
        </div>

        <div class="form-group">
          <label>Cell</label>
          <input name="cell" type="text" value="{{ old('cell') }}" class="form-control">
        </div>

        <div class="form-group">
          <label>Username</label>
          <input name="username" type="text" value="{{ old('username') }}" class="form-control">
        </div>

        <div class="form-group">
          <label for="role">Role</label>
          <select name="role" id="role" class="form-control">
            <option value="">-- Select --</option>
            @foreach ($roles as $role)
              <option value="{{ $role->id }}" {{ old('role') == $role->id ? 'selected' : '' }}>
                {{ $role->name }}
              </option>
            @endforeach
          </select>

          @error('role')
          <span class="text-danger"><b>* Required</b></span>
          @enderror

        </div>

        <div class="text-right">
          <button type="submit" class="btn btn-primary">Submit</button>
        </div>
      </form>
    </div>
  </div>
  <!-- End -->
@endsection
