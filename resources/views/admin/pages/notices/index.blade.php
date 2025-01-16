{{-- resources/views/admin/notices/index.blade.php --}}
@extends('admin.layouts.app')
@section('title', 'Notice Management Page')

@section('main-section')
  <div class="content container-fluid">
    <div class="page-header">
      <div class="row align-items-center">
        <div class="col">
          <h3 class="page-title">Notices</h3>
          <ul class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item active">Notices</li>
          </ul>
        </div>
        <div class="col-auto float-end ms-auto">
          <a href="{{ route('admin.notices.create') }}" class="btn btn-primary"><i class="fa fa-plus"></i> Add Notice</a>
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
                    <th>Description</th>
                    <th>File</th>
                    <th>Status</th>
                    <th>Expires</th>
                    <th>Actions</th>
                  </tr>
                </thead>
                <tbody>
                  @forelse($notices as $notice)
                    <tr>
                      <td>{{ $notice->title }}</td>
                      <td>{{ Str::limit($notice->description, 50) }}</td>
                      <td>
                        <span class="badge bg-info">{{ strtoupper($notice->file_type) }}</span>
                        <small>{{ number_format($notice->file_size / 1024, 2) }} KB</small>
                      </td>
                      <td>
                        <div class="status-toggle">
                          <input type="checkbox" id="status_{{ $notice->id }}" class="check"
                                 {{ $notice->status ? 'checked' : '' }}>
                          <label for="status_{{ $notice->id }}" class="checktoggle">checkbox</label>
                        </div>
                      </td>
                      <td>
                        @if($notice->expired_at)
                          {{ $notice->expired_at->format('M d, Y') }}
                        @else
                          <span class="text-muted">No expiry</span>
                        @endif
                      </td>
                      <td>
                        <div class="d-flex">
                          <a href="{{ Storage::url($notice->file_path) }}"
                             class="btn btn-sm btn-info me-2" target="_blank">
                            <i class="fa fa-eye"></i>
                          </a>
                          <form action="{{ route('admin.notices.trash', $notice) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger">
                              <i class="fa-solid fa-trash-can"></i>
                            </button>
                          </form>
                        </div>
                      </td>
                    </tr>
                  @empty
                    <tr>
                      <td colspan="8" class="text-center">No notices found</td>
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
