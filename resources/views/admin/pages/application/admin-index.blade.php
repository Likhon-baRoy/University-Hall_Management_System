{{-- resources/views/admin/pages/application/admin-index.blade.php --}}
@extends('admin.layouts.app')
@section('title', 'All Applications')

@section('main-section')
  <div class="content container-fluid">
    <div class="page-header">
      <div class="row align-items-center">
        <div class="col">
          <h3 class="page-title">All Applications</h3>
          <ul class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item active">Applications</li>
          </ul>
        </div>
        <div class="col-auto">
          <a href="{{ route('applications.archive') }}" class="btn btn-secondary">
            <i class="fas fa-archive"></i> View Archive
          </a>
        </div>
      </div>
    </div>

    <div class="card">
      <div class="card-body">
        @include('validate-main')
        <div class="table-responsive">
          <table class="table table-hover table-center mb-0" id="applications-table">
            <thead>
              <tr>
                <th>#</th>
                <th>Applicant</th>
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
                  <td>
                    @if($application->user)
                      <div class="fw-bold">{{ $application->user->name }}</div>
                      <small class="text-muted">{{ $application->user->user_type }}</small>
                    @else
                      <div class="fw-bold">N/A</div>
                      <small class="text-muted">No User Info</small>
                    @endif
                  </td>
                  <td>
                    @if($application->application_type === 'change')
                      <span class="badge bg-info">Change Request</span>
                    @else
                      <span class="badge bg-warning">Cancel Request</span>
                    @endif
                  </td>
                  <td>
                    @if($application->currentSeat && $application->currentSeat->room && $application->currentSeat->room->hall)
                      <div class="small">
                        <div><strong>Hall:</strong> {{ $application->currentSeat->room->hall->name }}</div>
                        <div><strong>Room:</strong> {{ $application->currentSeat->room->name }}</div>
                        <div><strong>Seat:</strong> {{ $application->currentSeat->name }}</div>
                      </div>
                    @else
                      <span class="text-muted">No Current Seat</span>
                    @endif
                  </td>
                  <td>
                    @if($application->application_type === 'change')
                      @if($application->requestedSeat && $application->requestedSeat->room && $application->requestedSeat->room->hall)
                        <div class="small">
                          <div><strong>Hall:</strong> {{ $application->requestedSeat->room->hall->name }}</div>
                          <div><strong>Room:</strong> {{ $application->requestedSeat->room->name }}</div>
                          <div><strong>Seat:</strong> {{ $application->requestedSeat->name }}</div>
                          @if(!$application->requestedSeat->status)
                            <div class="text-danger mt-1">
                              <i class="fas fa-exclamation-triangle"></i> Seat no longer available
                            </div>
                          @endif
                        </div>
                      @else
                        <span class="text-muted">N/A</span>
                      @endif
                    @else
                      <span class="text-muted">N/A</span>
                    @endif
                  </td>
                  <td>
                    <span class="badge bg-{{ $application->status === 'pending' ? 'warning' :
                                             ($application->status === 'approved' ? 'success' : 'danger') }}">
                      {{ ucfirst($application->status) }}
                    </span>
                  </td>
                  <td>
                    <div>{{ $application->created_at->format('M d, Y') }}</div>
                    <small class="text-muted">{{ $application->created_at->format('h:i A') }}</small>
                  </td>
                  <td>
                    @if($application->status === 'pending')
                      <a href="{{ route('applications.show', $application) }}"
                         class="btn btn-sm btn-primary">
                        <i class="fas fa-edit"></i> Process
                      </a>
                    @else
                      <a href="{{ route('applications.show', $application) }}"
                         class="btn btn-sm btn-info">
                        <i class="fas fa-eye"></i> View
                      </a>
                    @endif
                  </td>
                </tr>
              @empty
                <tr>
                  <td colspan="8" class="text-center">No applications found</td>
                </tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
@endsection

@push('styles')
<style>
 .badge {
   padding: 0.5em 0.75em;
 }
 .table td {
   vertical-align: middle;
 }
 .small div {
   line-height: 1.4;
 }
 /* Custom DataTable styling */
 .dataTables_filter {
   margin-bottom: 15px;
 }
 .dataTables_filter label {
   font-weight: normal;
   white-space: nowrap;
   text-align: left;
 }
 .dataTables_filter input {
   margin-left: 0.5em;
   display: inline-block;
   width: auto;
   height: calc(1.5em + 0.75rem + 2px);
   padding: 0.375rem 0.75rem;
   font-size: 1rem;
   font-weight: 400;
   line-height: 1.5;
   color: #495057;
   background-color: #fff;
   background-clip: padding-box;
   border: 1px solid #ced4da;
   border-radius: 0.25rem;
   transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
 }
</style>
@endpush

@push('scripts')
<script>
 $(document).ready(function() {
   if (!$.fn.DataTable.isDataTable('#applications-table')) {
     $('#applications-table').DataTable({
       pageLength: 25,
       order: [[6, 'desc']], // Sort by submitted date
       columnDefs: [
         { orderable: false, targets: [7] } // Disable sorting on action column
       ]
     });
   }
 });
</script>
@endpush
