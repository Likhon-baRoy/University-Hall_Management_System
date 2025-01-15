{{-- resources/views/admin/notices/trash.blade.php --}}
@extends('admin.layouts.app')
@section('title', 'Trashed Notices')

@section('main-section')
  <div class="content container-fluid">
    <div class="page-header">
      <div class="row align-items-center">
        <div class="col">
          <h3 class="page-title">Trashed Notices</h3>
          <ul class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('admin.notices.index') }}">Notices</a></li>
            <li class="breadcrumb-item active">Trash</li>
          </ul>
        </div>
      </div>
    </div>

    <div class="row">
      <div class="col-sm-12">
        <div class="card">
          <div class="card-body">
            <div class="table-responsive">
              <table class="table table-stripped">
                <thead>
                  <tr>
                    <th>Title</th>
                    <th>File</th>
                    <th>Deleted At</th>
                    <th>Actions</th>
                  </tr>
                </thead>
                <tbody>
                  @forelse($notices as $notice)
                    <tr>
                      <td>{{ $notice->title }}</td>
                      <td>
                        <span class="badge bg-info">{{ strtoupper($notice->file_type) }}</span>
                        <small>{{ number_format($notice->file_size / 1024, 2) }} KB</small>
                      </td>
                      <td>{{ $notice->deleted_at->format('M d, Y H:i') }}</td>
                      <td>
                        <div class="d-flex">
                          <form action="{{ route('admin.notices.restore', $notice->id) }}"
                                method="POST" class="me-2">
                            @csrf
                            <button type="submit" class="btn btn-sm btn-success"
                                    title="Restore Notice">
                              <i class="fa fa-undo"></i>
                            </button>
                          </form>
                          <form action="{{ route('admin.notices.force-delete', $notice->id) }}"
                                method="POST"
                                onsubmit="return confirm('Are you sure? This action cannot be undone.');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger"
                                    title="Permanently Delete">
                              <i class="fa fa-times"></i>
                            </button>
                          </form>
                        </div>
                      </td>
                    </tr>
                  @empty
                    <tr>
                      <td colspan="8" class="text-center">No trashed notices found</td>
                    </tr>
                  @endforelse
                </tbody>
              </table>
            </div>
            {{ $notices->links() }}
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection
