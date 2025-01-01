@extends('admin.layouts.app')

@section('main-section')

  <div class="row">
    <div class="col-lg-8">
      <div class="card">
        <div class="card-header d-flex justify-content-between">
          <h4 class="card-title">Admin Trash Users</h4>
          <a href="{{ route('admin-user.index') }}" class="text-success">Published Users <i class="fa fa-arrow-right"></i></a>
        </div>
        <div class="card-body">
          @include('validate-main')
          <div class="table-responsive">
            <table class="table mb-0">
              <thead>
                <tr>
                  <th>#</th>
                  <th>Name</th>
                  <th>Photo</th>
                  <th>Created At</th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody>

                @forelse ($all_admin as $item)

                  @if( $item -> name !== 'Provider' )
                    <tr>
                      <td>{{$loop -> index + 1}}</td>
                      <td>{{$item -> name}}</td>
                      <td>
                        @if ($item -> photo == 'avatar.png')
                          <img style="width: 60px; height:60px; object-fit:cover" src="{{ url('storage/avatar.png') }}" alt="">
                        @endif
                      </td>
                      <td>{{$item -> created_at}}</td>
                      <td>
                        <a class="btn btn-sm btn-info" href="{{ route('admin.trash.update', $item -> id) }}">Restor User</a>

                        <form method="POST" action="{{ route('admin-user.destroy', $item -> id) }}" class="d-inline delete-form">
                          @csrf
                          @method('DELETE')
                          <button class="btn btn-sm btn-danger" type="submit">Delete Permanently</button>
                        </form>

                      </td>
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

  </div>
  </div>

@endsection
