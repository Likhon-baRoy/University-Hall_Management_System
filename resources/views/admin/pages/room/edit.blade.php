@extends('admin.layouts.app')
@section('title', 'Edit Room')

@section('main-section')
  <div class="row">
    <div class="col-lg-12">
      <div class="card">
        <div class="card-header d-flex justify-content-between">
          <h4 class="card-title">Edit Room Details</h4>
          <a href="{{ route('hall-room.index') }}" class="btn btn-primary">
            <i class="fa fa-arrow-left"></i> Back to Rooms
          </a>
        </div>
        <div class="card-body">
          @include('validate')

          <form action="{{ route('hall-room.update', $room->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="row">
              <div class="col-md-6">
                <div class="form-group">
                  <label>Hall Name:</label>
                  <select name="hall_id" id="hall_id" class="form-control">
                    <option value="">-- Select Hall --</option>
                    @foreach ($halls as $hall)
                      <option value="{{ $hall->id }}" {{ $room->hall_id == $hall->id ? 'selected' : '' }}>
                        {{ $hall->name }}
                      </option>
                    @endforeach
                  </select>
                </div>

                <div class="form-group">
                  <label>Room Number:</label>
                  <input name="name" type="text" class="form-control" value="{{ $room->name }}">
                </div>

                <div class="form-group">
                  <label>Status:</label>
                  <select name="status" class="form-control">
                    <option value="1" {{ $room->status ? 'selected' : '' }}>Active</option>
                    <option value="0" {{ !$room->status ? 'selected' : '' }}>Inactive</option>
                  </select>
                </div>
              </div>

              <div class="col-md-6">
                <div class="form-group">
                  <label>Room Photo</label>
                  <div class="mb-3">
                    <img id="make-photo-preview"
                         src="{{ url('storage/image/room/' . ($room->photo ?? 'default-room.jpg')) }}"
                         alt="Room Photo"
                         style="max-width: 100%; max-height: 200px; object-fit: cover;">
                  </div>
                  <input type="file"
                         name="photo"
                         class="form-control"
                         id="photo-preview"
                         accept="image/jpeg,image/png,image/jpg,image/webp">
                  <small class="text-muted">Leave empty to keep current photo</small>
                </div>
              </div>
            </div>

            <div class="text-right mt-4">
              <a href="{{ route('hall-room.index') }}" class="btn btn-secondary">Cancel</a>
              <button type="submit" class="btn btn-primary">Update Room</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
@endsection
