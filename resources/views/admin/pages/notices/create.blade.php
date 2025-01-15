k{{-- resources/views/admin/notices/create.blade.php --}}
@extends('admin.layouts.app')
@section('title', 'Create New Notice')

@section('main-section')
  <div class="content container-fluid">
    <div class="page-header">
      <div class="row align-items-center">
        <div class="col">
          <h3 class="page-title">Add Notice</h3>
          <ul class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin.notices.index') }}">Notices</a></li>
            <li class="breadcrumb-item active">Add Notice</li>
          </ul>
        </div>
      </div>
    </div>

    <div class="row">
      <div class="col-md-12">
        <div class="card">
          <div class="card-body">
            <div class="card-body">
              @if(session('error'))
                <div class="alert alert-danger">
                  {{ session('error') }}
                </div>
              @endif

              @if(session('success'))
                <div class="alert alert-success">
                  {{ session('success') }}
                </div>
              @endif

              @if ($errors->any())
                <div class="alert alert-danger">
                  <ul>
                    @foreach ($errors->all() as $error)
                      <li>{{ $error }}</li>
                    @endforeach
                  </ul>
                </div>
              @endif

              <form action="{{ route('admin.notices.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="form-group mb-3">
                  <label class="form-label">Title <span class="text-danger">*</span></label>
                  <input type="text" name="title" class="form-control @error('title') is-invalid @enderror"
                         value="{{ old('title') }}" required>
                  @error('title')
                  <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
                </div>

                <div class="form-group mb-3">
                  <label class="form-label">Description</label>
                  <textarea name="description" rows="4"
                            class="form-control @error('description') is-invalid @enderror">{{ old('description') }}</textarea>
                  @error('description')
                  <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
                </div>

                <div class="form-group mb-3">
                  <label class="form-label">Notice File (PDF/DOC/DOCX) <span class="text-danger">*</span></label>
                  <input type="file" name="notice_file"
                         class="form-control @error('notice_file') is-invalid @enderror"
                         accept=".pdf,.doc,.docx" required>
                  @error('notice_file')
                  <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
                </div>

                <div class="form-group mb-3">
                  <label class="form-label">Expiry Date</label>
                  <input type="date" name="expired_at"
                         class="form-control @error('expired_at') is-invalid @enderror"
                         value="{{ old('expired_at') }}">
                  @error('expired_at')
                  <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
                </div>

                <div class="text-end">
                  <button type="submit" class="btn btn-primary">Save Notice</button>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
@endsection
