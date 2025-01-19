{{-- resources/views/admin/pages/application/archive.blade.php --}}
@extends('admin.layouts.app')
@section('title', 'Archived Applications')

@section('main-section')
  <div class="content container-fluid">
    <div class="page-header">
      <div class="row align-items-center">
        <div class="col">
          <h3 class="page-title">Archived Applications</h3>
          <ul class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('applications.index') }}">Applications</a></li>
            <li class="breadcrumb-item active">Archive</li>
          </ul>
        </div>
        <div class="col-auto">
          <a href="{{ route('applications.index') }}" class="btn btn-secondary"> All Applications </a>
        </div>
      </div>
    </div>

    <div class="card">
      <div class="card-body">
        <div class="table-responsive">
          <table class="table table-hover data-table-element archive-table">
            <thead>
              <tr>
                <th>#</th>
                <th>Applicant</th>
                <th>Type</th>
                <th>Locations</th>
                <th>Final Status</th>
                <th>Processed By</th>
                <th>Timeline</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody>
              @forelse($archivedApplications as $application)
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
                    <small>
                      <div class="mb-2">
                        <strong>From:</strong>
                        @if($application->currentSeat && $application->currentSeat->room && $application->currentSeat->room->hall)
                          {{ $application->currentSeat->room->hall->name }} -
                          Room {{ $application->currentSeat->room->name }}
                        @else
                          No previous seat
                        @endif
                      </div>
                      @if($application->application_type === 'change')
                        <div>
                          <strong>To:</strong>
                          @if($application->requestedSeat && $application->requestedSeat->room && $application->requestedSeat->room->hall)
                            {{ $application->requestedSeat->room->hall->name }} -
                            Room {{ $application->requestedSeat->room->name }}
                          @else
                            N/A
                          @endif
                        </div>
                      @endif
                    </small>
                  </td>
                  <td>
                    <span class="badge bg-{{ $application->status === 'approved' ? 'success' : 'danger' }}">
                      {{ ucfirst($application->status) }}
                    </span>
                  </td>
                  <td>
                    @if($application->processor)
                      <div>{{ $application->processor->name }}</div>
                      <small class="text-muted">{{ $application->processor->user_type }}</small>
                    @else
                      <span class="text-muted">N/A</span>
                    @endif
                  </td>
                  <td>
                    <small>
                      <div><strong>Submitted:</strong> {{ $application->created_at->format('M d, Y h:i A') }}</div>
                      <div>
                        <strong>Processed:</strong>
                        {{ $application->processed_at ? $application->processed_at->format('M d, Y h:i A') : 'N/A' }}
                      </div>
                      <div>
                        <strong>Archived:</strong>
                        {{ $application->deleted_at ? $application->deleted_at->format('M d, Y h:i A') : 'N/A' }}
                      </div>
                    </small>
                  </td>
                  <td>
                    <form action="{{ route('applications.force-delete', $application) }}"
                          method="POST"
                          class="delete-form d-inline">
                      @csrf
                      @method('DELETE')
                      <button type="submit" class="btn btn-sm btn-danger">
                        <i class="fa-solid fa-trash-can"></i>
                      </button>
                    </form>
                  </td>
                </tr>
              @empty
                <tr>
                  <td colspan="8" class="text-center">No archived applications found</td>
                </tr>
              @endforelse
            </tbody>
          </table>
        </div>
        {{ $archivedApplications->links() }}
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
 .timeline div {
   line-height: 1.4;
 }
 .btn-group {
   white-space: nowrap;
 }
 .btn-group .btn {
   float: none;
   display: inline-block;
 }
</style>
@endpush

@push('scripts')
<script>
 $(document).ready(function() {
   if (!$.fn.DataTable.isDataTable('.archive-table')) {
     $('.archive-table').DataTable({
       pageLength: 25,
       order: [[6, 'desc']], // Sort by timeline
       columnDefs: [
         { orderable: false, targets: [7] } // Disable sorting on action column
       ]
     });
   }
 });
</script>
@endpush
