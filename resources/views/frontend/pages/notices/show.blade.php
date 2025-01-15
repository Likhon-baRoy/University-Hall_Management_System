{{-- resources/views/frontend/notices/show.blade.php --}}
{{-- This is the partial view that gets loaded via AJAX --}}
<h4 class="card-title">{{ $currentNotice->title }}</h4>
<p class="text-muted">Published: {{ $currentNotice->published_at->format('M d, Y') }}</p>
<p>{{ $currentNotice->description }}</p>
<div class="ratio ratio-16x9">
  <object
    data="{{ Storage::url($currentNotice->file_path) }}#toolbar=0&navpanes=0&scrollbar=0"
    type="application/pdf"
    width="100%"
    height="600px">
    <p>Unable to display PDF file. <a href="{{ Storage::url($currentNotice->file_path) }}">Download</a> instead.</p>
  </object>
</div>
