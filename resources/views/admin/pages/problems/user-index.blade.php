{{-- admin/pages/problems/user-index.blade.php --}}
@extends('admin.layouts.app')
@section('title', 'My Problems')

@section('main-section')
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between">
              <h4 class="card-title">My Problems</h4>
              <a href="{{ route('problems.create') }}" class="btn btn-primary">Create New Problem</a>
            </div>
            <div class="card-body">
                @include('validate-main')
                <div class="table-responsive">
                    <table class="table mb-0 data-table-element">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Title</th>
                                <th>Type</th>
                                <th>Location</th>
                                <th>Priority</th>
                                <th>Status</th>
                                <th>Admin Response</th>
                                <th>Created</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                          @forelse($problems as $problem)
                            <tr>
                              <td>{{ $loop->index + 1 }}</td>
                              <td>{{ $problem->title }}</td>
                              <td>{{ ucfirst($problem->issue_type) }}</td>
                              <td>{{ $problem->location }}</td>
                              <td>
                                <span class="badge badge-{{ $problem->priority === 'urgent' ? 'danger' :
                                                            ($problem->priority === 'high' ? 'warning' :
                                                              ($problem->priority === 'medium' ? 'info' : 'success')) }}">
                                  {{ ucfirst($problem->priority) }}
                                </span>
                              </td>
                              <td>
                                <span class="badge badge-{{ $problem->status === 'pending' ? 'warning' :
                                                            ($problem->status === 'in_progress' ? 'info' :
                                                              ($problem->status === 'resolved' ? 'success' : 'secondary')) }}">
                                  {{ ucfirst(str_replace('_', ' ', $problem->status)) }}
                                </span>
                              </td>
                              <td>{{ $problem->admin_response ? 'Yes' : 'No' }}</td>
                              <td>{{ $problem->created_at->diffForHumans() }}</td>
                              <td>
                                <a href="{{ route('problems.show', $problem) }}" class="btn btn-sm btn-info">
                                  <i class="fa fa-eye"></i>
                                </a>
                              </td>
                            </tr>
                            @empty
                                <tr>
                                  <td colspan="9" class="text-center">No problems found</td>
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
@endsection
