{{-- admin/pages/problems/trashed.blade.php --}}
@extends('admin.layouts.app')
@section('title', 'Trashed Problems')

@section('main-section')
  <div class="container-fluid">
    <div class="row">
      <div class="col-lg-12">
        <div class="card">
          <div class="card-header d-flex justify-content-between">
            <h4 class="card-title">Trashed Problems</h4>
            <a href="{{ route('problems.index') }}" class="btn btn-primary">Back to Problems</a>
          </div>
          <div class="card-body">
            @include('validate-main')
            <div class="table-responsive">
              <table class="table mb-0 data-table-element">
                <thead>
                  <tr>
                    <th>User</th>
                    <th>Title</th>
                    <th>Type</th>
                    <th>Status</th>
                    <th>Deleted At</th>
                    <th>Action</th>
                  </tr>
                </thead>
                <tbody>
                  @forelse($problems as $problem)
                    <tr>
                      <td>{{ $problem->user->name }}</td>
                      <td>{{ $problem->title }}</td>
                      <td>{{ ucfirst($problem->issue_type) }}</td>
                      <td>
                        <span class="badge badge-{{ $problem->status === 'pending' ? 'warning' :
                                                    ($problem->status === 'in_progress' ? 'info' :
                                                      ($problem->status === 'resolved' ? 'success' : 'secondary')) }}">
                          {{ ucfirst(str_replace('_', ' ', $problem->status)) }}
                        </span>
                      </td>
                      <td>{{ $problem->deleted_at->diffForHumans() }}</td>
                      <td>
                        <form action="{{ route('problems.restore', $problem->id) }}"
                              method="POST"
                              class="d-inline">
                          @csrf
                          <button type="submit"
                                  class="btn btn-sm btn-info"
                                  onclick="return confirm('Are you sure you want to restore this problem?')">
                            <i class="fa fa-undo"></i>
                          </button>
                        </form>
                        <form action="{{ route('problems.force-delete', $problem->id) }}"
                              method="POST"
                              class="d-inline">
                          @csrf
                          @method('DELETE')
                          <button type="submit"
                                  class="btn btn-sm btn-danger"
                                  onclick="return confirm('This action cannot be undone. Are you sure?')">
                            <i class="fa fa-trash"></i>
                          </button>
                        </form>
                      </td>
                    </tr>
                  @empty
                    <tr>
                      <td colspan="8" class="text-center">No trashed problems found</td>
                    </tr>
                  @endforelse
                </tbody>
              </table>
            </div>
            {{ $problems->links() }}
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection
