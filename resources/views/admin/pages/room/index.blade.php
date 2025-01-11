@extends('admin.layouts.app')
@section('title', 'Room Page')

@section('main-section')

  <div class="row">
    <div class="col-lg-8">
      <div class="card">
        <div class="card-header d-flex justify-content-between">
          <h4 class="card-title text-center" style="flex-grow: 1; color: #007bff;">All Rooms</h4>
          <a href="{{ route('hall-room.trash') }}" class="btn btn-warning">
            <i class="fa fa-trash"></i> Trash
          </a>
        </div>
        <div class="card-body">
          @include('validate-main')
          <div class="table-responsive">
            <table class="table mb-0 data-table-element">
              <thead>
                <tr>
                  <th>Hall Name</th>
                  <th>Room</th>
                  <th>Photo</th>
                  <th>Status</th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody>
                @forelse ($rooms as $room)
                  <tr class="{{ (!$room->status || ($room->hall && !$room->hall->status)) ? 'table-warning' : '' }}">

                    @if($room->hall)
                      <td>
                        {{ $room->hall->name }}
                        @if($room->hall->deleted_at)
                          <span class="badge badge-danger">Hall Trashed</span>
                        @elseif(!$room->hall->status)
                          <span class="badge badge-warning">Hall Inactive</span>
                        @endif
                      </td>

                      <td>{{ $room->name }}</td>

                      <td>
                        <img style="width:60px;height:60px;object-fit:cover;"
                             src="{{ url('storage/image/room/' . ($room->photo ?? 'default-room.jpg')) }}"
                             alt="">
                      </td>

                      <!-- Only show edit button if hall is active -->
                      @if($room->hall && $room->hall->status && !$room->hall->deleted_at)
                        <td>
                          @if($room->deleted_at)
                            <span class="badge badge-danger">Trashed</span>
                          @else
                            @if($room -> status )
                              <span class="badge badge-success">Published</span>
                              <a class="text-danger" href="{{ route('room.status.update', $room -> id ) }}"><i class="fa fa-times"></i></a>
                            @else
                              <span class="badge badge-danger">unpublished</span>
                              <a class="text-success" href="{{ route('room.status.update', $room -> id ) }}"><i class="fa fa-check"></i></a>
                            @endif
                          @endif
                        </td>

                        <td>
                          <a class="btn btn-sm btn-warning"
                             href="{{ route('hall-room.edit', $room->id) }}">
                            <i class="fa fa-edit"></i>
                          </a>

                          <form action="{{ route('hall-room.destroy', $room->id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger">
                              <i class="fa fa-trash"></i>
                            </button>
                          </form>
                        </td>
                      @else
                        <td><span class="text-danger">N/A</span></td>
                        <td><span class="text-danger">N/A</span></td>
                      @endif

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
            <h4 class="card-title">Add new Room</h4>
          </div>
          <div class="card-body">
            @include('validate')
            <form action="{{ route('hall-room.store') }}" method="POST" enctype="multipart/form-data">
              @csrf

              <div class="form-group">
                <label>Hall Name:</label>
                <select name="hall_id" id="hall_id" class="form-control">
                  <option value="">-- Select --</option>
                  @foreach ($halls as $hall)
                    @if($hall->status && !$hall->deleted_at)
                      <option value="{{ $hall->id }}" {{ old('hall_id') == $hall->id ? 'selected' : '' }}>
                        {{ $hall->name }}
                      </option>
                    @endif
                  @endforeach
                </select>
              </div>

              <div class="form-group">
                <label>Room Start No:</label>
                <input name="start" type="text" value="{{ old('start') }}" class="form-control">
              </div>

              <div class="form-group">
                <label>Room End No:</label>
                <input name="end" type="text" value="{{ old('end') }}" class="form-control">
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
