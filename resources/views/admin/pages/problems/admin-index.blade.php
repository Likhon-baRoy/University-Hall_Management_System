{{-- admin/pages/problems/admin-index.blade.php --}}
@extends('admin.layouts.app')
@section('title', 'All Problems')

@section('main-section')
  {{-- Add this before the table in admin-index.blade.php --}}
  <div class="row mb-4">
    <div class="col-md-3">
      <div class="card bg-info text-white">
        <div class="card-body">
          <h5 class="card-title">Total Problems</h5>
          <p class="card-text h2">{{ App\Models\Problem::count() }}</p>
        </div>
      </div>
    </div>
    <div class="col-md-3">
      <div class="card bg-warning text-white">
        <div class="card-body">
          <h5 class="card-title">Pending Problems</h5>
          <p class="card-text h2">{{ App\Models\Problem::where('status', 'pending')->count() }}</p>
        </div>
      </div>
    </div>
    <div class="col-md-3">
      <div class="card bg-success text-white">
        <div class="card-body">
          <h5 class="card-title">Resolved Problems</h5>
          <p class="card-text h2">{{ App\Models\Problem::whereIn('status', ['resolved', 'closed'])->count() }}</p>
        </div>
      </div>
    </div>
    <div class="col-md-3">
      <div class="card bg-danger text-white">
        <div class="card-body">
          <h5 class="card-title">Trashed Problems</h5>
          <p class="card-text h2">{{ App\Models\Problem::onlyTrashed()->count() }}</p>
        </div>
      </div>
    </div>
  </div>

  <div class="row">
    <div class="col-lg-12">
      <div class="card">

        {{-- Add this after the card header in admin-index.blade.php --}}
        <div class="card-header border-bottom">
          <form action="{{ route('problems.index') }}" method="GET" class="row">
            <div class="col-md-3">
              <select name="status" class="form-control">
                <option value="">All Status</option>
                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="in_progress" {{ request('status') == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                <option value="resolved" {{ request('status') == 'resolved' ? 'selected' : '' }}>Resolved</option>
                <option value="closed" {{ request('status') == 'closed' ? 'selected' : '' }}>Closed</option>
              </select>
            </div>
            <div class="col-md-3">
              <select name="priority" class="form-control">
                <option value="">All Priorities</option>
                <option value="low" {{ request('priority') == 'low' ? 'selected' : '' }}>Low</option>
                <option value="medium" {{ request('priority') == 'medium' ? 'selected' : '' }}>Medium</option>
                <option value="high" {{ request('priority') == 'high' ? 'selected' : '' }}>High</option>
                <option value="urgent" {{ request('priority') == 'urgent' ? 'selected' : '' }}>Urgent</option>
              </select>
            </div>
            <div class="col-md-3">
              <select name="issue_type" class="form-control">
                <option value="">All Types</option>
                <option value="hall" {{ request('issue_type') == 'hall' ? 'selected' : '' }}>Hall</option>
                <option value="room" {{ request('issue_type') == 'room' ? 'selected' : '' }}>Room</option>
                <option value="seat" {{ request('issue_type') == 'seat' ? 'selected' : '' }}>Seat</option>
                <option value="other" {{ request('issue_type') == 'other' ? 'selected' : '' }}>Other</option>
              </select>
            </div>
            <div class="col-md-2">
              <button type="submit" class="btn btn-primary">Filter</button>
              <a href="{{ route('problems.index') }}" class="btn btn-secondary">Reset</a>
            </div>
            <a href="{{ route('problems.trashed') }}" class="btn btn-warning justify-content-between align-items-center"><i class="fa fa-trash"></i> Trash</a>
          </form>
        </div>

        <div class="card-body">
          @include('validate-main')
          <div class="table-responsive">
            <table class="table mb-0 data-table-element">
              <thead>
                <tr>
                  <th>#</th>
                  <th>User</th>
                  <th>Title</th>
                  <th>Type</th>
                  <th>Location</th>
                  <th>Priority</th>
                  <th>Status</th>
                  <th>Created</th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody>
                @forelse($problems as $problem)
                  <tr>
                    <td>{{ $loop->index + 1 }}</td>
                    <td>{{ $problem->user?->name ?? 'N/A' }}</td>
                    <td>{{ $problem->title ?? 'No Title' }}</td>
                    <td>{{ ucfirst($problem->issue_type ?? 'N/A') }}</td>
                    <td>{{ $problem->location ?? 'No Location' }}</td>
                    <td>
                      <span class="badge badge-{{ $problem->priority === 'urgent' ? 'danger' :
                                                  ($problem->priority === 'high' ? 'warning' :
                                                    ($problem->priority === 'medium' ? 'info' : 'success')) }}">
                        {{ ucfirst($problem->priority ?? 'low') }}
                      </span>
                    </td>
                    <td>
                      <span class="badge badge-{{ $problem->status === 'pending' ? 'warning' :
                                                  ($problem->status === 'in_progress' ? 'info' :
                                                    ($problem->status === 'resolved' ? 'success' : 'secondary')) }}">
                        {{ ucfirst(str_replace('_', ' ', $problem->status ?? 'unknown')) }}
                      </span>
                    </td>
                    <td>{{ $problem->created_at ? $problem->created_at->diffForHumans() : 'N/A' }}</td>
                    <td>
                      <a href="{{ route('problems.show', $problem) }}" class="btn btn-sm btn-info">
                        <i class="fa fa-eye"></i>
                      </a>
                      <form action="{{ route('problems.trash', $problem) }}"
                            method="POST"
                            class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-danger">
                          <i class="fa fa-trash"></i>
                        </button>
                      </form>
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
