{{-- resources/views/admin/pages/application/user-index.blade.php --}}
@extends('admin.layouts.app')
@section('title', 'My Applications')

@section('main-section')
  <div class="content container-fluid">
    <div class="page-header">
      <div class="row align-items-center">
        <div class="col">
          <h3 class="page-title">My Applications</h3>
          <ul class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item active">My Applications</li>
          </ul>
        </div>
        <div class="col-auto float-end ms-auto">
          <a href="{{ route('applications.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> New Application
          </a>
        </div>
      </div>
    </div>

    <div class="row">
      <div class="col-sm-12">
        <div class="card">
          <div class="card-body">
            <div class="table-responsive">
              <table class="table table-hover table-center mb-0 data-table-element">
                <thead>
                  <tr>
                    <th>#</th>
                    <th>Type</th>
                    <th>Current Location</th>
                    <th>Requested Location</th>
                    <th>Status</th>
                    <th>Submitted</th>
                    <th>Action</th>
                  </tr>
                </thead>
                <tbody>
                  @forelse($applications as $application)
                    <tr>
                      <td>{{ $loop->index + 1 }}</td>
                      <td>{{ ucfirst($application->application_type) }}</td>
                      <td>
                        @if($application->currentSeat)
                          {{ $application->currentSeat->room->hall->name }} -
                          Room {{ $application->currentSeat->room->name }} -
                          Seat {{ $application->currentSeat->name }}
                        @else
                          N/A
                        @endif
                      </td>
                      <td>
                        @if($application->application_type === 'change' && $application->requestedSeat)
                          {{ $application->requestedSeat->room->hall->name }} -
                          Room {{ $application->requestedSeat->room->name }} -
                          Seat {{ $application->requestedSeat->name }}
                        @else
                          N/A
                        @endif
                      </td>
                      <td>
                        <span class="badge bg-{{ $application->status === 'pending' ? 'warning' :
                                                 ($application->status === 'approved' ? 'success' : 'danger') }}">
                          {{ ucfirst($application->status) }}
                        </span>
                      </td>
                      <td>{{ $application->created_at->diffForHumans() }}</td>
                      <td>
                        <a href="{{ route('applications.show', $application) }}" class="btn btn-sm btn-info">
                          View Details
                        </a>
                      </td>
                    </tr>
                  @empty
                    <tr>
                      <td colspan="7" class="text-center">No applications found</td>
                    </tr>
                  @endforelse
                </tbody>
              </table>
            </div>
            {{ $applications->links() }}
          </div>
        </div>
      </div>
    </div>
    </div>
  </div>
@endsection
