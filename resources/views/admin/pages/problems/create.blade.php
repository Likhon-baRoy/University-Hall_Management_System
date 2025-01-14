{{-- admin/pages/problems/create.blade.php --}}
@extends('admin.layouts.app')
@section('title', 'Create Problem')

@section('main-section')
  <div class="row">
    <div class="col-lg-12">
      <div class="card">
        <div class="card-header">
          <h4 class="card-title">Create New Problem</h4>
        </div>
        <div class="card-body">
          <form action="{{ route('problems.store') }}" method="POST">
            @csrf
            @include('validate')

            <div class="form-group">
              <label>Title</label>
              <input type="text" name="title" class="form-control" value="{{ old('title') }}">
            </div>

            <div class="form-group">
              <label>Description</label>
              <textarea name="description" class="form-control" rows="4">{{ old('description') }}</textarea>
            </div>

            <div class="form-group">
              <label>Issue Type</label>
              <select name="issue_type" class="form-control">
                <option value="">Select Type</option>
                <option value="hall" {{ old('issue_type') === 'hall' ? 'selected' : '' }}>Hall</option>
                <option value="room" {{ old('issue_type') === 'room' ? 'selected' : '' }}>Room</option>
                <option value="seat" {{ old('issue_type') === 'seat' ? 'selected' : '' }}>Seat</option>
                <option value="other" {{ old('issue_type') === 'other' ? 'selected' : '' }}>Other</option>
              </select>
            </div>

            <div class="form-group">
              <label>Location</label>
              <input type="text" name="location" class="form-control" value="{{ old('location') }}">
            </div>

            <div class="form-group">
              <label>Priority</label>
              <select name="priority" class="form-control">
                <option value="">Select Priority</option>
                <option value="low" {{ old('priority') === 'low' ? 'selected' : '' }}>Low</option>
                <option value="medium" {{ old('priority') === 'medium' ? 'selected' : '' }}>Medium</option>
                <option value="high" {{ old('priority') === 'high' ? 'selected' : '' }}>High</option>
                <option value="urgent" {{ old('priority') === 'urgent' ? 'selected' : '' }}>Urgent</option>
              </select>
            </div>

            <div class="text-right">
              <a href="{{ route('problems.index') }}" class="btn btn-secondary">Cancel</a>
              <button type="submit" class="btn btn-primary">Submit Problem</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
@endsection
