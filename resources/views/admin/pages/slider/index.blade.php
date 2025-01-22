@extends('admin.layouts.app')
@section('title', 'Slider Page')

@section('main-section')

  <div class="row">
    <div class="col-lg-8">
      <div class="card">
        <div class="card-header d-flex justify-content-between">
          <h4 class="card-title">All Sliders</h4>
          <a href="{{ route('slider.trash') }}" class="btn btn-warning"><i class="fa-solid fa-trash-can"></i> Trashed Slides</a>
        </div>
        <div class="card-body">
          @include('validate-main')
          <div class="table-responsive">
            <table class="table mb-0 data-table-element">
              <thead>
                <tr>
                  <th>S.N.</th>
                  <th>Title</th>
                  <th>Photo</th>
                  <th>Buttons</th>
                  <th>Status</th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody>

                @forelse ($sliders as $item)
                  <tr>
                    <td>{{ $loop -> index + 1 }}</td>
                    <td>{{ $item -> title }}</td>
                    <td><img style="width:60px;height:60px;object-fit:cover;" src="{{ url('storage/image/slider/' . $item -> photo ) }}" alt=""></td>
                    <td>
                      @if($item->btns)
                        @php $buttons = json_decode($item->btns) @endphp
                        <span class="badge bg-info">{{ count($buttons) }} buttons</span>
                        <div class="small text-muted mt-1">
                          @foreach($buttons as $btn)
                            <div>• {{ $btn->btn_title }}</div>
                          @endforeach
                        </div>
                      @else
                        <span class="badge bg-secondary">No buttons</span>
                      @endif
                    </td>
                    <td>
                      @if($item -> status )
                        <span class="badge badge-success">Published</span>
                        <a class="text-danger" href="{{ route('slider.status.update', $item -> id ) }}"><i class="fa fa-times"></i></a>
                      @else
                        <span class="badge badge-danger">unpublished</span>
                        <a class="text-success" href="{{ route('slider.status.update', $item -> id ) }}"><i class="fa fa-check"></i></a>
                      @endif
                    </td>
                    <td>
                      <a class="btn btn-sm btn-warning" href="{{ route('slider.edit', $item -> id ) }}"><i class="fa fa-edit"></i></a>
                      <a href="{{ route('slider.trash.update', $item -> id) }}" class="btn btn-sm btn-danger"><i class="fa-solid fa-trash-can"></i></a>
                    </td>
                  </tr>
                @empty
                  <tr>
                    <td class="text-center text-danger" colspan="6">No Records Found</td>
                  </tr>
                @endforelse

              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
    <div class="col-md-4">

      @if( $form_type == 'create')
        <div class="card">
          <div class="card-header">
            <h4 class="card-title">Add new Slide</h4>
          </div>
          <div class="card-body">
            @include('validate')
            <form action="{{ route('slider.store') }}" method="POST" enctype="multipart/form-data">
              @csrf
              <div class="form-group">
                <label>Title:</label>
                <input name="title" type="text" value="{{ old('title') }}" class="form-control" placeholder="Enter slider title" required>
              </div>
              <div class="form-group">
                <label>Subtitle:</label>
                <input name="subtitle" value="{{ old('subtitle') }}" type="text" class="form-control" placeholder="Enter slider subtitle">
              </div>
              <div class="form-group">
                <label>Description:</label>
                <textarea name="description" class="form-control" rows="4"
                          placeholder="Enter slider description">{{ old('description') }}</textarea>
              </div>
              <div class="form-group">
                <label>Slide Photo:</label>
                <div class="mb-3">
                  <img id="make-photo-preview"
                       src="" alt="<Upload Photo>"
                       style="max-width: 100%; max-height: 200px; object-fit: cover;">
                </div>
                <input type="file"
                       name="photo"
                       class="form-control"
                       id="photo-preview"
                       accept="image/jpeg,image/jpg">
                <small class="text-muted">Leave empty to keep current photo</small>
              </div>
              <hr>
              <div class="form-group slider-btn-opt">
                <a id="add-new-slider-button" class="btn btn-sm btn-info" href="#">
                  <i class="fas fa-plus"></i> Add Buttons
                </a>
              </div>
              <div class="text-right">
                <button type="submit" class="btn btn-primary">Submit</button>
              </div>
            </form>
          </div>
        </div>
      @endif

      @if( $form_type == 'edit')
        <div class="card">
          <div class="card-header d-flex justify-content-between">
            <h4 class="card-title">Edit Slide</h4>
            <a href="{{ route('slider.index') }}" class="btn btn-primary">
              <i class="fa fa-arrow-left"></i> Go Back
            </a>
          </div>
          <div class="card-body">
            @include('validate')
            <form action="{{ route('slider.update', $slider -> id ) }}" method="POST" enctype="multipart/form-data">
              @csrf
              @method('PUT')
              <div class="form-group">
                <label>Title:</label>
                <input name="title" type="text" value="{{ $slider->title }}" class="form-control">
              </div>

              <div class="form-group">
                <label>Subtitle:</label>
                <input name="subtitle" value="{{ $slider->subtitle }}" type="text" class="form-control">
              </div>

              <div class="form-group">
                <label>Description:</label>
                <textarea name="description" class="form-control" rows="3">{{ $slider->description }}</textarea>
              </div>

              <div class="form-group">
                <label>Photo</label>
                <div class="mb-3">
                  <img id="make-photo-preview"
                       src="{{ url('storage/image/slider/' . ($slider->photo ?? 'default.jpg')) }}"
                       alt="Room Photo"
                       style="max-width: 100%; max-height: 200px; object-fit: cover;">
                </div>
                <input type="file"
                       name="photo"
                       class="form-control"
                       id="photo-preview"
                       accept="image/jpeg,image/png,image/jpg,image/webp">
                <small class="text-muted">Leave empty to keep current photo</small>
              </div>
              <hr>
              <div class="form-group slider-btn-opt">
                @php $i = 1; @endphp
                @foreach ( json_decode($slider->btns) as $btn)
                  <div class="btn-opt-area">
                    <span>Button:{{ $i }}</span>
                    <span class="badge badge-danger remove-btn" style="margin-left:300px;cursor:pointer;">remove</span>
                    <input class="form-control" name="btn_title[]" value="{{ $btn -> btn_title }}" type="text" placeholder="Button Title">
                    <input class="form-control" value="{{ $btn -> btn_link }}" name="btn_link[]" type="text" placeholder="Button Link">
                    <select class="form-control" name="btn_type[]">
                      <option @if( $btn -> btn_type === 'btn-light-out' ) selected @endif value="btn-light-out"> Default </option>
                      <option @if( $btn -> btn_type === 'btn-color btn-full' ) selected @endif  value="btn-color btn-full"> SkyBlue </option>
                    </select>
                    <hr />
                  </div>
                  @php $i++; @endphp
                @endforeach

                <a id="add-new-slider-button" class="btn btn-sm btn-info" href="#">Add slider button</a>
              </div>

              <div class="text-right">
                <button type="submit" class="btn btn-primary">Submit</button>
              </div>
            </form>
          </div>
        </div>
      @endif

    </div>
  </div>
@endsection
