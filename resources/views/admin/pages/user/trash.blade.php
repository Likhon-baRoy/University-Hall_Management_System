@extends('admin.layouts.app')
@section('title', 'Trashed Users')

@section('main-section')

  <div class="row">
    <div class="col-lg-8">
      <div class="card">
        <div class="card-header d-flex justify-content-between">
          <h4 class="card-title">Trashed Users</h4>
          <a href="{{ route('admin-user.index') }}" class="btn btn-primary">Published Users</a>
        </div>
        <div class="card-body">
          @include('validate-main')
          <div class="table-responsive">
            <table class="table mb-0 data-table-element">
              <thead>
                <tr>
                  <th>Name</th>
                  <th>Department</th>
                  <th>Photo</th>
                  <th>Created At</th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody>

                @forelse ($users as $user)
                  <tr>
                    <td>{{$user -> name}}</td>
                    <td>{{$user -> dept}}</td>
                    <td>
                      <img style="width: 60px; height:60px; object-fit:cover" src="{{ url('storage/image/profile/' . ($user->photo ?? 'avatar.png')) }}" alt="">
                    </td>
                    <td>{{$user -> created_at}}</td>
                    <td>
                      <form action="{{ route('admin-user.restore', $user->id) }}"
                            method="POST"
                            style="display: inline;">
                        @csrf
                        <button type="submit" class="btn btn-sm btn-info">
                          <i class="fa fa-undo"></i>
                        </button>
                      </form>

                      <form action="{{ route('admin-user.force-delete', $user->id) }}"
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
