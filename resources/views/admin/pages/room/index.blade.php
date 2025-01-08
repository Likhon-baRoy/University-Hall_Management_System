@extends('admin.layouts.app')
@section('title', 'Room Page')

@section('main-section')

  <div class="row">
    <div class="col-lg-8">
      <div class="card">
        <div class="card-header d-flex justify-content-between">
          <h4 class="card-title">All Rooms</h4>
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
                  <tr>
                    <td>{{ $room->hall->name }}</td>
                    <td>{{ $room -> name }}</td>
                    <td>{{ $room -> photo }}</td>
                    <td>{{ $room -> status }}</td>
                    <td>
                      <a class="btn btn-sm btn-warning" href="{{ route('hall-room.edit', $room->id) }}">
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
                    <option value="{{ $hall->id }}" {{ old('hall_id') == $hall->id ? 'selected' : '' }}>
                      {{ $hall->name }}
                    </option>
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

      @if( $form_type == 'edit')

        <div class="card">
          <div class="card-header">
            <h4 class="card-title">Edit Room</h4>
          </div>

          <div class="card-body">
            @include('validate')

            <form action="{{ route('hall-room.update', $room -> id ) }}" method="POST" enctype="multipart/form-data">
              @csrf
              @method('PUT')

              <div class="form-group">
                <label>Hall Name:</label>
                <select name="hall_id" id="hall_id" class="form-control">
                  <option value="">-- Select --</option>
                  @foreach ($halls as $hall)
                    <option value="{{ $hall->id }}"
                            {{ ($room->hall_id == $hall->id) ? 'selected' : '' }}>
                      {{ $hall->name }}
                    </option>
                  @endforeach
                </select>
              </div>

              <div class="form-group">
                <label>Room No:</label>
                <input name="name" type="text" value="{{ $room -> name }}" class="form-control">
              </div>

              <div class="form-group">
                <label>Photo</label>
                <br>
                <img style="max-width:100%;" id="slider-photo-preview" src="{{ url('storage/image/room/' . $room -> photo) }}" alt="Room image">
                <br>
                <input style="display:none;" name="photo" type="file" class="form-control" id="slider-photo">
                <label for="slider-photo">
                  <img style="width: 80px; cursor: pointer;" src="https://icon-library.com/images/image-icon/image-icon-2.jpg" alt="">
                </label>
              </div>
              <hr>

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
