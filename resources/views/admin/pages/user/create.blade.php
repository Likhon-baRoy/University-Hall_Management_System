@extends('admin.layouts.app')
@section('title', 'Add New User')

@section('main-section')
  <div class="row">
    <div class="col-lg-8">
      <div class="card">
        <div class="card-header">
          <h4 class="card-title">Add New User</h4>
        </div>
        <div class="card-body">
          @include('validate')
          <form action="{{route('admin-user.store')}}" method="POST">
            @csrf
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
              <input class="form-control" type="email" name="email" id="email" placeholder="Email" value="{{ old('email') }}" required>
            </div>

            <!-- Cell -->
            <div class="form-group mb-3">
              <input class="form-control" type="text" name="cell" placeholder="Cell" value="{{ old('cell') }}" required>
            </div>

            <div class="form-group mb-3">
              <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="gender" id="male" value="male" {{ old('gender') == 'male' ? 'checked' : '' }} required>
                <label class="form-check-label" for="male">Male</label>
              </div>
              <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="gender" id="female" value="female" {{ old('gender') == 'female' ? 'checked' : '' }}>
                <label class="form-check-label" for="female">Female</label>
              </div>
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
    </div>
  </div>
@endsection
