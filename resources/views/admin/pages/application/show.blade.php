{{-- admin/pages/application/show.blade.php --}}
@extends('admin.layouts.app')
@section('title', 'View Application')

@section('main-section')
  <div class="row">
    <div class="col-lg-8">
      <div class="card">
        <div class="card-header d-flex justify-content-between">
          <h4 class="card-title">Application Details #{{ $application->id }}</h4>
          <a href="{{ route('applications.index') }}" class="btn btn-secondary">Back to List</a>
        </div>
        <div class="card-body">
          @include('validate-main')

          <div class="application-details">
            <div class="mb-4">
              <span class="badge bg-{{ $application->status === 'pending' ? 'warning' :
                                       ($application->status === 'approved' ? 'success' : 'danger') }}">
                {{ ucfirst($application->status) }}
              </span>
              <span class="badge bg-info">{{ ucfirst($application->application_type) }} Request</span>
            </div>

            <div class="card bg-light mb-4">
              <div class="card-body">
                <h6>Applicant Information</h6>
                <p><strong>Name:</strong> {{ $application->user->name }}</p>
                <p><strong>User Type:</strong> {{ $application->user->user_type }}</p>
                <p><strong>Submitted:</strong> {{ $application->created_at->format('M d, Y h:i A') }}</p>
              </div>
            </div>

            <div class="card bg-light mb-4">
              <div class="card-body">
                <h6>Current Location</h6>
                @if($application->currentSeat)
                  <p>{{ $application->currentSeat->room->hall->name }} -
                    Room {{ $application->currentSeat->room->name }} -
                    Seat {{ $application->currentSeat->name }}</p>
                @else
                  <p class="text-muted">No current seat assigned</p>
                @endif

                @if($application->application_type === 'change')
                  <h6 class="mt-4">Requested Location</h6>
                  @if($application->requestedSeat)
                    <p>{{ $application->requestedSeat->room->hall->name }} -
                      Room {{ $application->requestedSeat->room->name }} -
                      Seat {{ $application->requestedSeat->name }}</p>

                    {{-- Check if requested seat is available --}}
                    @if(!$application->requestedSeat->status)
                      <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle"></i>
                        This seat is no longer available!
                      </div>
                    @endif
                  @else
                    <p class="text-muted">No seat specified</p>
                  @endif
                @endif
              </div>
            </div>

            <div class="card bg-light mb-4">
              <div class="card-body">
                <h6>Reason for Application</h6>
                <p>{{ $application->reason }}</p>
              </div>
            </div>

            @if($application->status !== 'pending')
              <div class="card bg-light">
                <div class="card-body">
                  <h6>Processing Details</h6>
                  <p><strong>Processed By:</strong> {{ $application->processor->name ?? 'N/A' }}</p>
                  <p><strong>Processed At:</strong> {{ $application->processed_at ? $application->processed_at->format('M d, Y h:i A') : 'N/A' }}</p>
                  <p><strong>Admin Note:</strong></p>
                  <p>{{ $application->admin_note ?? 'No note provided' }}</p>
                </div>
              </div>
            @endif
          </div>
        </div>

        {{-- In show.blade.php - Improved processing section --}}
        @if(auth()->user()->role && in_array('Applications', json_decode(auth()->user()->role->permissions ?? '[]')))
          @if($application->status === 'pending')
            <div class="card-footer">
              <form id="processNoteForm" class="mb-3">
                <div class="form-group">
                  <label class="form-label">Admin Note <span class="text-danger">*</span></label>
                  <textarea id="adminNote" class="form-control" rows="3" required
                            placeholder="Provide a detailed explanation for your decision..."></textarea>
                </div>
              </form>

              <div class="alert alert-info">
                <i class="fas fa-info-circle me-2"></i>
                After processing:
                <ul class="mb-0 mt-2">
                  <li>The applicant will receive an email notification with your decision and note</li>
                  <li>This application will be moved to archive after 7 days</li>
                  @if($application->application_type === 'change' && $application->requestedSeat)
                    <li>Current seat status will be automatically updated</li>
                  @endif
                </ul>
              </div>

              <div class="d-flex justify-content-end gap-2">
                {{-- For Seat Cancellation --}}
                @if($application->application_type === 'cancel')
                  <form action="{{ route('applications.cancel', $application) }}"
                        method="POST"
                        class="process-form">
                    @csrf
                    @method('DELETE')
                    <input type="hidden" name="admin_note" class="admin-note-input">
                    <button type="submit"
                            class="btn btn-success process-btn"
                            data-action="approve"
                            data-message="This will delete the user's seat assignment!">
                      <i class="fas fa-check"></i> Approve Cancellation
                    </button>
                  </form>
                @endif

                {{-- For Seat Change --}}
                @if($application->application_type === 'change')
                  <form action="{{ route('applications.process', $application->id) }}"
                        method="POST"
                        class="process-form">
                    @csrf
                    <input type="hidden" name="status" value="approved">
                    <input type="hidden" name="admin_note" class="admin-note-input">
                    <button type="submit"
                            class="btn btn-success process-btn"
                            data-action="approve"
                            {{ !$application->requestedSeat || !$application->requestedSeat->status ? 'disabled' : '' }}
                            data-message="Are you sure you want to approve this seat change?">
                      <i class="fas fa-check"></i> Approve Change
                    </button>
                  </form>
                @endif

                {{-- Reject Button (for both types) --}}
                <form action="{{ route('applications.process', $application) }}"
                      method="POST"
                      class="process-form">
                  @csrf
                  <input type="hidden" name="status" value="rejected">
                  <input type="hidden" name="admin_note" class="admin-note-input">
                  <button type="submit"
                          class="btn btn-danger process-btn"
                          data-action="reject"
                          data-message="Are you sure you want to reject this application?">
                    <i class="fas fa-times"></i> Reject
                  </button>
                </form>
              </div>
            </div>

            @push('scripts')
            <script>
             document.querySelectorAll('.process-form').forEach(form => {
               form.addEventListener('submit', function(e) {
                 e.preventDefault();

                 const adminNote = document.getElementById('adminNote').value;
                 if (!adminNote.trim()) {
                   alert('Please provide an admin note before processing.');
                   return;
                 }

                 const btn = this.querySelector('.process-btn');
                 const action = btn.dataset.action;
                 const message = btn.dataset.message;

                 if (confirm(message)) {
                   this.querySelector('.admin-note-input').value = adminNote;
                   this.submit();
                 }
               });
             });
            </script>
            @endpush
          @endif
        @endif
      </div>
    </div>
  </div>
@endsection

@section('custom-css')
  <style>
   .gap-2 {
     gap: 0.5rem;
   }
  </style>
@endsection
