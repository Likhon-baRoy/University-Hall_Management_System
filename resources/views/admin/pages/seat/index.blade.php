@extends('admin.layouts.app')
@section('title', 'Seat Page')

@section('main-section')

  <div class="row">
    <div class="col-lg-8">
      <div class="card">
        <div class="card-header d-flex justify-content-between">
          <h4 class="card-title">All Seats</h4>
        </div>
        <div class="card-body">
          @include('validate-main')
          <div class="table-responsive">
            <table class="table mb-0 data-table-element">
              <thead>
                <tr>
                  <th>#</th>
                  <th>Hall</th>
                  <th>Seat No</th>
                  <th>Created At</th>
                  <th>Status</th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody>

                @forelse ($seats as $item)
                  <tr>
                    <td>{{ $loop -> index + 1 }}</td>
                    <td>{{ $item->name }}</td>
                    <td>{{ $item -> name }}</td>
                    <td>{{ $item -> created_at -> diffForHumans() }}</td>

                    <td>
                      <a class="btn btn-sm btn-warning" href="{{ route('hall-seat.edit', $item -> id ) }}"><i class="fa fa-edit"></i></a>
                    </td>

                  </tr>
                @empty

                @endforelse

              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
    <div class="col-md-4">

      @if( $form_type == 'create')
        <div class="card">
          <div class="card-header">
            <h4 class="card-title">Add new Seat</h4>
          </div>
          <div class="card-body">
            @include('validate')
            <form action="{{ route('hall-seat.store') }}" method="POST" enctype="multipart/form-data">
              @csrf

              <div class="form-group">
                <label>Room Name:</label>
                <select name="room_id" id="room_id" class="form-control">
                  <option value="">-- Select --</option>
                  @foreach ($rooms as $room)
                    <option value="{{ $room->id }}" {{ old('room') == $room->id ? 'selected' : '' }}>
                      {{ $room->hall?->name . '-' . $room->name }}
                    </option>
                  @endforeach
                </select>
              </div>

              <div class="form-group">
                <label>Seat Start No:</label>
                <input name="start" type="text" value="{{ old('start') }}" class="form-control">
              </div>


              <div class="form-group">
                <label>Seat End No:</label>
                <input name="end" type="text" value="{{ old('end') }}" class="form-control">
              </div>

              <div class="text-right">
                <button type="submit" class="btn btn-primary">Submit</button>
              </div>
            </form>
          </div>
        </div>
      @endif

      @if( $form_type == 'edit')
        <div class="card">
          <div class="card-header">
            <h4 class="card-title">Edit Slide</h4>
          </div>
          <div class="card-body">
            @include('validate')
            <form action="{{ route('hall-seat.update', $seat -> id ) }}" method="POST" enctype="multipart/form-data">
              @csrf
              @method('PUT')
              <div class="form-group">
                <label>Seat No:</label>
                <input name="name" type="text" value="{{ $seat -> name }}" class="form-control">
              </div>

              <div class="text-right">
                <button type="submit" class="btn btn-primary">Submit</button>
              </div>

            </form>
          </div>
        </div>
      @endif

    </div>
  </div>
@endsection
