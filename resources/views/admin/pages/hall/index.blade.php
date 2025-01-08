@extends('admin.layouts.app')
@section('title', 'Hall Page')

@section('main-section')

  <div class="row">
    <div class="col-lg-8">
      <div class="card">
        <div class="card-header d-flex justify-content-between">
          <h4 class="card-title">All Halls</h4>
          <a href="{{ route('hall.trash') }}" class="btn btn-warning">
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
                  <th>For Gender</th>
                  <th>Location</th>
                  <th>Status</th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody>

                @forelse ($halls as $item)
                  <tr>
                    <td>{{ $item -> name }}</td>
                    <td>{{ $item -> gender }}</td>
                    <td>{{ $item -> location }}</td>

                    <td>
                      @if($item -> status )
                        <span class="badge badge-success">Published</span>
                        <a class="text-danger" href="{{ route('hall.status.update', $item -> id ) }}"><i class="fa fa-times"></i></a>
                      @else
                        <span class="badge badge-danger">unpublished</span>
                        <a class="text-success" href="{{ route('hall.status.update', $item -> id ) }}"><i class="fa fa-check"></i></a>
                      @endif
                    </td>

                    <td>
                      <a class="btn btn-sm btn-warning" href="{{ route('hall.edit', $item -> id ) }}"><i class="fa fa-edit"></i></a>

                      <form action="{{ route('hall.destroy', $item->id) }}" method="POST" style="display:inline;">
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
            <h4 class="card-title">Add new Hall</h4>
          </div>
          <div class="card-body">
            @include('validate')
            <form action="{{ route('hall.store') }}" method="POST" enctype="multipart/form-data">
              @csrf
              <div class="form-group">
                <label>Hall Name:</label>
                <input name="name" type="text" value="{{ old('name') }}" class="form-control">
              </div>

              <div class="form-group">
                <label>For Gender:</label>
                <input name="gender" value="{{ old('gender') }}" type="text" class="form-control">
              </div>

              <div class="form-group">
                <label>Location:</label>
                <input name="location" value="{{ old('location') }}" type="text" class="form-control">
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
            <form action="{{ route('hall.update', $hall -> id ) }}" method="POST" enctype="multipart/form-data">
              @csrf
              @method('PUT')
              <div class="form-group">
                <label>Hall Name:</label>
                <input name="name" type="text" value="{{ $hall -> name }}" class="form-control">
              </div>

              <div class="form-group">
                <label>For Gender:</label>
                <input name="gender" value="{{ $hall -> gender }}" type="text" class="form-control">
              </div>

              <div class="form-group">
                <label>Location:</label>
                <input name="location" value="{{ $hall -> location }}" type="text" class="form-control">
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
