@extends('admin.layouts.app')
@section('title', 'Edit Seat')

@section('main-section')
  <div class="row">
    <div class="col-lg-12">
      <div class="card">
        <div class="card-header d-flex justify-content-between">
          <h4 class="card-title">Edit Seat Details</h4>
          <a href="{{ route('hall-seat.index') }}" class="btn btn-primary">
            <i class="fa fa-arrow-left"></i> Back to Seats
          </a>
        </div>
        <div class="card-body">
          @include('validate')

          <form action="{{ route('hall-seat.update', $seat->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="row">
              <div class="col-md-6">
                <div class="form-group">
                  <label>Room:</label>
                  <select name="room_id" id="room_id" class="form-control">
                    <option value="">-- Select Room --</option>
                    @foreach ($rooms as $room)
                      <option value="{{ $room->id }}" {{ $seat->room_id == $room->id ? 'selected' : '' }}>
                        {{ $room->hall->name }} - Room {{ $room->name }}
                      </option>
                    @endforeach
                  </select>
                </div>

                <div class="form-group">
                  <label>Seat Number:</label>
                  <input name="name" type="text" class="form-control" value="{{ $seat->name }}">
                </div>

                <div class="form-group">
                  <label>Status:</label>
                  <select name="status" class="form-control">
                    <option value="1" {{ $seat->status ? 'selected' : '' }}>Active</option>
                    <option value="0" {{ !$seat->status ? 'selected' : '' }}>Inactive</option>
                  </select>
                </div>
              </div>
            </div>

            <div class="text-right mt-4">
              <a href="{{ route('hall-seat.index') }}" class="btn btn-secondary">Cancel</a>
              <button type="submit" class="btn btn-primary">Update Seat</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
@endsection
