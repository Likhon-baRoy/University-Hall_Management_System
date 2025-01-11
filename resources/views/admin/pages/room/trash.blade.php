@extends('admin.layouts.app')
@section('title', 'Trashed Rooms')

@section('main-section')
  <div class="row">
    <div class="col-lg-12">
      <div class="card">
        <div class="card-header d-flex justify-content-between">
          <h4 class="card-title">Trashed Rooms</h4>
          <a href="{{ route('hall-room.index') }}" class="btn btn-primary">Back to Rooms</a>
        </div>
        <div class="card-body">
          @include('validate-main')
          <div class="table-responsive">
            <table class="table mb-0 data-table-element">
              <thead>
                <tr>
                  <th>Hall</th>
                  <th>Room</th>
                  <th>Deleted At</th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody>
                @forelse ($rooms as $room)
                  <tr>
                    <td>{{ $room->hall->name }}</td>
                    <td>{{ $room->name }}</td>
                    <td>{{ $room->deleted_at->format('Y-m-d H:i:s') }}</td>
                    <td>
                      <form action="{{ route('hall-room.restore', $room->id) }}"
                            method="POST"
                            style="display: inline;">
                        @csrf
                        <button type="submit" class="btn btn-sm btn-info">
                          <i class="fa fa-undo"></i>
                        </button>
                      </form>

                      <form action="{{ route('hall-room.force-delete', $room->id) }}"
                            method="POST"
                            style="display: inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                                class="btn btn-sm btn-danger"
                                onclick="return confirm('Are you sure? This cannot be undone!')">
                          <i class="fa fa-times"></i>
                        </button>
                      </form>
                    </td>
                  </tr>
                @empty
                  <tr>
                    <td colspan="4" class="text-center">No trashed rooms found</td>
                  </tr>
                @endforelse
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection
