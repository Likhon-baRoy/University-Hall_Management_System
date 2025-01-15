{{-- resources/views/frontend/notices/index.blade.php --}}
@extends('frontend.layouts.app')
@section('title', 'Notice Page')

@section('main-section')
  <div class="container py-5">
    <div class="row">
      <div class="col-lg-8">
        <div class="card shadow-sm">
          <div class="card-body">
            @if($latestNotice)
              <div id="notice-preview">
                <h4 class="card-title">{{ $latestNotice->title }}</h4>
                <p class="text-muted">Published: {{ $latestNotice->published_at->format('M d, Y') }}</p>
                <p>{{ $latestNotice->description }}</p>
                <div class="ratio ratio-16x9">
                  <object
                    data="{{ Storage::url($latestNotice->file_path) }}"
                    type="application/pdf"
                    width="100%"
                    height="600px">
                    <p>Unable to display PDF file. <a href="{{ Storage::url($latestNotice->file_path) }}">Download</a> instead.</p>
                  </object>
                </div>
              </div>
            @else
              <p class="text-center">No notices available</p>
            @endif
          </div>
        </div>
      </div>

      <div class="col-lg-4">
        <div class="card shadow-sm">
          <div class="card-header">
            <h5 class="card-title mb-0">Recent Notices</h5>
          </div>
          <div class="card-body">
            <div class="list-group list-group-flush">
              @foreach($recentNotices as $notice)
                <a href="{{ route('notices.show', $notice) }}"
                   class="list-group-item list-group-item-action d-flex justify-content-between align-items-center
                         {{ $latestNotice && $latestNotice->id === $notice->id ? 'active' : '' }}">
                  <div>
                    <h6 class="mb-1">{{ $notice->title }}</h6>
                    <small class="text-muted">{{ $notice->published_at->format('M d, Y') }}</small>
                  </div>
                  <i class="fe fe-chevron-right"></i>
                </a>
              @endforeach
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  @push('scripts')
  <script>
   document.addEventListener('DOMContentLoaded', function() {
     // Add AJAX functionality to load notices dynamically
     const noticeLinks = document.querySelectorAll('.list-group-item');
     const noticePreview = document.getElementById('notice-preview');

     noticeLinks.forEach(link => {
       link.addEventListener('click', function(e) {
         e.preventDefault();
         fetch(this.href)
           .then(response => response.text())
           .then(html => {
             noticePreview.innerHTML = html;
             // Update active state
             noticeLinks.forEach(l => l.classList.remove('active'));
             this.classList.add('active');
             // Update URL without page reload
             history.pushState({}, '', this.href);
           });
       });
     });
   });
  </script>
  @endpush
@endsection
