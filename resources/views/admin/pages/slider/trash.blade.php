@extends('admin.layouts.app')

@section('main-section')

  <div class="row">
    <div class="col-lg-8">
      <div class="card">
        <div class="card-header d-flex justify-content-between">
          <h4 class="card-title">All Sliders </h4>
          <a href="{{route('slider.index')}}" class="btn btn-sm btn-success">Published Slides <i class="fa fa-arrow-right"></i></a>
        </div>
        @include('validate-main')
        <div class="card-body">
          <div class="table-responsive">
            <table class="table mb-0 data-table-search">
              <thead>
                <tr>
                  <th>#</th>
                  <th>Title</th>
                  <th>Photo</th>
                  <th>Created At</th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody>

                @forelse ($slider_data as $item)
                  <tr>
                    <td>{{$loop -> index + 1}}</td>
                    <td>{{$item -> title}}</td>
                    <td>
                      <img style="width: 100px; object-fit: cover;" src="{{url('storage/sliders/' . $item -> photo)}}" alt="">
                    </td>
                    <td>{{$item -> created_at -> DiffForHumans()}}</td>

                    <td>
                      <td>

                        {{-- <a class="btn btn-sm btn-info" href="#"><i class="fa fa-eye"></i></a>
                                                                  <a class="btn btn-sm btn-warning" href="{{route('admin-user.edit', $item -> id)}}"><i class="fa fa-edit"></i></a> --}}

                        {{-- Trash --}}
                        <a class="btn btn-sm btn-info" href="{{ route('slider.trash.update', $item -> id) }}">Restor User</a>
                        <form action="{{route('slider.destroy', $item -> id)}}" method="POST" class="d-inline delete-form">
                          @csrf
                          @method('DELETE')
                          <button class="btn btn-sm btn-danger" type="submit">Delete Permanently</button>
                        </form>
                      </td>
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


@endsection
