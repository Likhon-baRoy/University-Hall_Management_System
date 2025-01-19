{{-- resources/views/admin/pages/applications/create.blade.php --}}
@extends('admin.layouts.app')
@section('title', 'Create Seat Application')

@section('main-section')
  <div class="content container-fluid">
    <div class="page-header">
      <div class="row align-items-center">
        <div class="col">
          <h3 class="page-title">Create Seat Application</h3>
          <ul class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('applications.index') }}">Applications</a></li>
            <li class="breadcrumb-item active">Applications</li>
          </ul>
        </div>
        <div class="col-auto float-end ms-auto">
          <a href="{{ route('applications.user.index') }}" class="btn btn-primary"> Go Back </a>
        </div>
      </div>
    </div>

    <div class="row">
      <div class="col-md-12">
        <div class="card">
          <div class="card-body">
            @if(!$currentSeat)
              <div class="alert alert-warning">
                You don't have any seat assigned currently. You can only apply for seat cancellation if you have a seat.
              </div>
            @endif

            <form action="{{ route('applications.store') }}" method="POST">
              @csrf

              <!-- Application Type -->
              <div class="form-group mb-3">
                <label for="application_type">Application Type <span class="text-danger">*</span></label>
                <select name="application_type" id="application_type" class="form-select @error('application_type') is-invalid @enderror" required>
                  <option value="">Select Application Type</option>
                  @if($currentSeat)
                    <option value="change" {{ old('application_type') == 'change' ? 'selected' : '' }}>Seat Change</option>
                    <option value="cancel" {{ old('application_type') == 'cancel' ? 'selected' : '' }}>Seat Cancellation</option>
                  @else
                    <option value="change" {{ old('application_type') == 'change' ? 'selected' : '' }}>Seat Change</option>
                  @endif
                </select>
                @error('application_type')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>

              <!-- Current Seat Information (Read-only) -->
              <div class="form-group mb-3">
                <label>Current Seat</label>
                <input type="text" class="form-control" readonly
                       value="{{ $currentSeat ? $currentSeat->room->hall->name . ' - Room ' . $currentSeat->room->name . ' - Seat ' . $currentSeat->name : 'No seat assigned' }}">
              </div>

              <!-- Requested Seat (Only for seat change) -->
              <div id="requested_seat_section" class="form-group mb-3" style="display: none;">
                <label for="requested_seat_id">Requested Seat <span class="text-danger">*</span></label>
                <select name="requested_seat_id" id="requested_seat_id" class="form-select @error('requested_seat_id') is-invalid @enderror">
                  <option value="">Select Requested Seat</option>
                  @if($availableSeats)
                    @foreach($availableSeats as $seat)
                      <option value="{{ $seat->id }}" {{ old('requested_seat_id') == $seat->id ? 'selected' : '' }}>
                        {{ $seat->room->hall->name }} - Room {{ $seat->room->name }} - Seat {{ $seat->name }}
                      </option>
                    @endforeach
                  @endif
                </select>
                @error('requested_seat_id')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>

              <!-- Reason -->
              <div class="form-group mb-3">
                <label for="reason">Reason for Application <span class="text-danger">*</span></label>
                <textarea name="reason" id="reason" class="form-control @error('reason') is-invalid @enderror" rows="4" required>{{ old('reason') }}</textarea>
                <small class="form-text text-muted">Please provide a detailed explanation (minimum 50 characters)</small>
                @error('reason')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>

              <!-- Submit Button -->
              <div class="text-end">
                <button type="submit" class="btn btn-primary">Submit Application</button>
              </div>
            </form>
          </div>
        </div>
      </div>
        </div>
      </div>
  </div>
@endsection

@push('scripts')
<script>
 document.addEventListener('DOMContentLoaded', function() {
   const applicationType = document.getElementById('application_type');
   const requestedSeatSection = document.getElementById('requested_seat_section');
   const requestedSeatSelect = document.getElementById('requested_seat_id');

   function toggleRequestedSeat() {
     if (applicationType.value === 'change') {
       requestedSeatSection.style.display = 'block';
       requestedSeatSelect.required = true;
     } else {
       requestedSeatSection.style.display = 'none';
       requestedSeatSelect.required = false;
     }
   }

   applicationType.addEventListener('change', toggleRequestedSeat);
   toggleRequestedSeat(); // Initial state
 });
</script>
@endpush
