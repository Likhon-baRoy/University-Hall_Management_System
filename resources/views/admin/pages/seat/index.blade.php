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
                  @if($seat->room && $seat->room->hall)
                    <tr class="{{ (!$seat->status || !$seat->room->status || !$seat->room->hall->status || $seat->room->deleted_at || $seat->room->hall->deleted_at) ? 'table-warning' : '' }}">
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
                      <td>
                        @if($seat->status)
                          <span class="badge badge-success">Active</span>
                        @else
                          <span class="badge badge-danger">Inactive</span>
                        @endif
                      </td>
                      <td>
                        @if($seat->room->hall->status && !$seat->room->hall->deleted_at && $seat->room->status && !$seat->room->deleted_at)
                          <a class="btn btn-sm btn-warning" href="{{ route('hall-seat.edit', $seat->id) }}">
                            <i class="fa fa-edit"></i>
                          </a>
                          <form action="{{ route('hall-seat.destroy', $seat->id) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                    class="btn btn-sm btn-danger"
                                    onclick="return confirm('Are you sure? This cannot be undone!')">
                              <i class="fa fa-trash"></i>
                            </button>
                          </form>
                        @else
                          <span class="badge badge-warning">No actions available</span>
                        @endif
                      </td>
                    </tr>
                  @else
                    <tr>
                      <td class="text-center text-danger" colspan="5">Invalid Seat Record (Missing Room or Hall)</td>
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
      @if($form_type == 'create')
        <div class="card">
          <div class="card-header">
            <h4 class="card-title">Add new Seat</h4>
          </div>
          <div class="card-body">
            @include('validate')
            <form action="{{ route('hall-seat.store') }}" method="POST">
              @csrf
              <div class="form-group">
                <label>Room Name:</label>
                <select name="room_id" id="room_id" class="form-control">
                  <option value="">-- Select --</option>
                  @forelse ($rooms as $room)
                    @if($room->hall && $room->status && !$room->deleted_at && $room->hall->status && !$room->hall->deleted_at)
                      <option value="{{ $room->id }}" {{ old('room_id') == $room->id ? 'selected' : '' }}>
                        {{ $room->hall->name . ' - ' . $room->name }}
                      </option>
                    @endif
                  @empty
                    <option disabled>No active rooms available</option>
                  @endforelse
                </select>
              </div>

              <div class="form-group">
                <label>Seat Start No:</label>
                <input name="start" type="number" value="{{ old('start') }}" class="form-control" min="1">
              </div>

              <div class="form-group">
                <label>Seat End No:</label>
                <input name="end" type="number" value="{{ old('end') }}" class="form-control" min="1">
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
