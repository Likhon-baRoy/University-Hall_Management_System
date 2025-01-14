{{-- admin/pages/problems/show.blade.php --}}
@extends('admin.layouts.app')
@section('title', 'View Problem')

@section('main-section')
<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header d-flex justify-content-between">
                <h4 class="card-title">Problem Details</h4>
                <a href="{{ route('problems.index') }}" class="btn btn-secondary">Back to List</a>
            </div>
            <div class="card-body">
                @include('validate-main')

              <div class="problem-details">
                <h5>{{ $problem->title }}</h5>
                <p class="text-muted">
                  Problemed by {{ $problem->user->name }} · {{ $problem->created_at->diffForHumans() }}
                </p>

                <div class="my-3">
                  <span class="badge badge-{{ $problem->priority === 'urgent' ? 'danger' :
                                              ($problem->priority === 'high' ? 'warning' :
                                                ($problem->priority === 'medium' ? 'info' : 'success')) }}">
                    {{ ucfirst($problem->priority) }}
                  </span>
                  <span class="badge badge-{{ $problem->status === 'pending' ? 'warning' :
                                              ($problem->status === 'in_progress' ? 'info' :
                                                ($problem->status === 'resolved' ? 'success' : 'secondary')) }}">
                    {{ ucfirst(str_replace('_', ' ', $problem->status)) }}
                  </span>
                  <span class="badge badge-secondary">{{ ucfirst($problem->issue_type) }}</span>
                </div>

                <div class="card bg-light">
                  <div class="card-body">
                    <h6>Description:</h6>
                    <p>{{ $problem->description }}</p>

                    <h6>Location:</h6>
                    <p>{{ $problem->location }}</p>
                  </div>
                </div>

                @if($problem->admin_response)
                  <div class="card bg-light mt-4">
                    <div class="card-body">
                      <h6>Admin Response:</h6>
                      <p>{{ $problem->admin_response }}</p>
                      @if($problem->handledBy)
                        <small class="text-muted">
                          Response by {{ $problem->handledBy->name }}
                          @if($problem->resolved_at)
                            · {{ $problem->resolved_at->diffForHumans() }}
                          @endif
                        </small>
                      @endif
                    </div>
                  </div>
                @endif
              </div>
            </div>
        </div>
    </div>

    @if(auth()->user()->role && in_array('problems', json_decode(auth()->user()->role->permissions ?? '[]')))
      <div class="col-lg-4">
        <div class="card">
          <div class="card-header">
            <h4 class="card-title">Update Problem Status</h4>
          </div>
          <div class="card-body">
            <form action="{{ route('problems.update', $problem) }}" method="POST">
              @csrf
              @method('PUT')
              @include('validate')

              <div class="form-group">
                <label>Status</label>
                <select name="status" class="form-control">
                  <option value="pending" {{ $problem->status === 'pending' ? 'selected' : '' }}>Pending</option>
                  <option value="in_progress" {{ $problem->status === 'in_progress' ? 'selected' : '' }}>In Progress</option>
                  <option value="resolved" {{ $problem->status === 'resolved' ? 'selected' : '' }}>Resolved</option>
                  <option value="closed" {{ $problem->status === 'closed' ? 'selected' : '' }}>Closed</option>
                </select>
              </div>

              <div class="form-group">
                <label>Response</label>
                <textarea name="admin_response" class="form-control" rows="4">{{ $problem->admin_response }}</textarea>
              </div>

              <div class="text-right">
                <button type="submit" class="btn btn-primary">Update Problem</button>
              </div>
            </form>
          </div>
        </div>
      </div>
    @endif

    {{-- In show.blade.php, add this in the admin section --}}
    @if(auth()->user()->role && in_array('problems', json_decode(auth()->user()->role->permissions ?? '[]')))
      <div class="card-footer">
        <form action="{{ route('problems.trash', $problem) }}"
              method="POST"
              class="d-inline float-right">
          @csrf
          @method('DELETE')
          <button type="submit"
                  class="btn btn-danger"
                  onclick="return confirm('Are you sure you want to move this problem to trash?')">
            <i class="fa fa-trash"></i> Delete Problem
          </button>
        </form>
      </div>
    @endif
</div>
@endsection
