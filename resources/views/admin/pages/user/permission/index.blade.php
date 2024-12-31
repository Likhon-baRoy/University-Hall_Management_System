@extends('admin.layouts.app')

@section('main-section')

    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">All Permission</h4>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table mb-0">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Name</th>
                                    <th>Slug</th>
                                    <th>Created At</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>

                                @forelse ($all_permission as $per)

                                <tr>
                                    <td>{{$loop -> index + 1}}</td>
                                    <td>{{$per -> name}}</td>
                                    <td>{{$per -> slug}}</td>
                                    <td>{{$per -> created_at -> diffForHumans()}}</td>
                                    <td>
                                        <a class="btn btn-sm btn-info" href="#"><i class="fa fa-eye"></i></a>
                                        <a class="btn btn-sm btn-warning" href="#"><i class="fa fa-edit"></i></a>

                                        <form method="POST" action="{{ route('permission.destroy', $per -> id) }}" class="d-inline">
                                          @csrf
                                          @method('DELETE')
                                          <button class="btn btn-sm btn-danger" type="submit"><i class="fa fa-trash"></i></button>
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
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Add New Permission</h4>
                </div>
                <div class="card-body">
                  <form action="{{ route('permission.store') }}" method="POST">
                    @csrf
                    <div class="form-group">
                      @include('validate')
                      <label>Name</label>
                      <input name="name" type="text" class="form-control">
                    </div>
                    <div class="text-right">
                      <button type="submit" class="btn btn-primary">Submit</button>
                    </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection
