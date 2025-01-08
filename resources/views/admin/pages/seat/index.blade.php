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
                  <th>Hall</th>
                  <th>Room</th>
                  <th>Seat</th>
                  <th>Status</th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody>

                @forelse ($seats as $seat)
                  <tr class="{{ ($seat->status || ($seat->room->status && $seat->room->hall->status)) ? 'table-warning' : '' }}">

                    @if($seat->hall && $seat->room)
                      <td>
                        {{ $seat->room->hall->name }}
                        @if($seat->room->hall->deleted_at)
                          <span class="badge badge-danger">Hall Trashed</span>
                        @elseif(!$seat->room->hall->status)
                          <span class="badge badge-warning">Hall Inactive</span>
                        @endif
                      </td>
                      <td>
                        {{ $seat->room->name }}
                        @if($seat->room->deleted_at)
                          <span class="badge badge-danger">Room Trashed</span>
                        @elseif(!$seat->room->status)
                          <span class="badge badge-warning">Room Inactive</span>
                        @endif
                      </td>
                      <td>{{ $seat->name }}</td>
                      <td>{{ $seat->status ? 'Active' : 'Inactive' }}</td>

                      <!-- Only show edit button if hall and room is active -->
                      @if(($seat->room->hall->status && !$seat->room->hall->deleted_at) && (($seat->room->status && !$seat->room->deleted_at)))
                      <td>
                        <a class="btn btn-sm btn-warning" href="{{ route('hall-seat.edit', $seat->id) }}">
                          <i class="fa fa-edit"></i>
                        </a>
                      </td>
                      @endif
                    @else
                      <td class="text-center text-danger" colspan="5">No Records Found</td>
                    @endif
                  </tr>
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
                  @forelse ($rooms as $room)
                    @if($seat->hall && $seat->room && $room->status && !$room->deleted_at && $room->hall->status && !$room->hall->deleted_at)
                      <option value="{{ $room->id }}" {{ old('room_id') == $room->id ? 'selected' : '' }}>
                        {{ $room->hall->name . '-' . $room->name }}
                      </option>
                    @endif
                  @empty
                    <tr>
                      <td class="text-center text-danger" colspan="5">No Records Found</td>
                    </tr>
                  @endforelse
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
            <h4 class="card-title">Edit Seat</h4> <!-- Changed from "Edit Slide" -->
          </div>
          <div class="card-body">
            @include('validate')
            <form action="{{ route('hall-seat.update', $seat->id) }}" method="POST" enctype="multipart/form-data">
              @csrf
              @method('PUT')

              <div class="form-group">
                <label>Room Name:</label>
                <select name="room_id" id="room_id" class="form-control">
                  <option value="">-- Select --</option>
                  @foreach ($rooms as $room)
                    @if($room->status && !$room->deleted_at && $room->hall->status && !$room->hall->deleted_at)
                      <option value="{{ $room->id }}" {{ old('room_id') == $room->id ? 'selected' : '' }}>
                        {{ $room->hall->name . '-' . $room->name }}
                      </option>
                    @endif
                  @endforeach
                </select>
              </div>

              <div class="form-group">
                <label>Seat No:</label>
                <input name="name" type="text" value="{{ old('name', $seat->name) }}" class="form-control">
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
