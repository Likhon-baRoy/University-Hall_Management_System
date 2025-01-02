@extends('admin.layouts.app')
@section('title', 'Slider Page')

@section('main-section')

  <div class="row">
    <div class="col-lg-8">
      <div class="card">
        <div class="card-header d-flex justify-content-between">
          <h4 class="card-title">All Sliders</h4>
          <a href="{{ route('slider.trash') }}" class="btn btn-sm btn-danger">Trash Slides <i class="fa fa-arrow-right"></i></a>
        </div>
        <div class="card-body">
          @include('validate-main')
          <div class="table-responsive">
            <table class="table mb-0 data-table-element">
              <thead>
                <tr>
                  <th>#</th>
                  <th>Hall Name</th>
                  <th>Room No</th>
                  <th>Seat No</th>
                  <th>For Gender</th>
                  <th>Photo</th>
                  <th>Created At</th>
                  <th>Status</th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody>

                @forelse ($sliders as $item)
                  <tr>
                    <td>{{ $loop -> index + 1 }}</td>
                    <td>{{ $item -> hall }}</td>
                    <td>{{ $item -> room }}</td>
                    <td>{{ $item -> seat }}</td>
                    <td>{{ $item -> gender }}</td>
                    <td><img style="width:60px;height:60px;object-fit:cover;" src="{{ url('storage/sliders/' . $item -> photo ) }}" alt=""></td>
                    <td>{{ $item -> created_at -> diffForHumans() }}</td>
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

                      <a href="{{ route('slider.trash.update', $item -> id) }}" class="btn btn-sm btn-danger"><i class="fa fa-trash"></i></a>

                    </td>
                  </tr>
                @empty

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
                <label>Hall Name:</label>
                <input name="hall" type="text" value="{{ old('hall') }}" class="form-control">
              </div>

              <div class="form-group">
                <label>Room No:</label>
                <input name="room" value="{{ old('room') }}" type="text" class="form-control">
              </div>


              <div class="form-group">
                <label>Seat No:</label>
                <input name="seat" value="{{ old('seat') }}" type="text" class="form-control">
              </div>

              <div class="form-group">
                <label>For Gender:</label>
                <input name="gender" value="{{ old('gender') }}" type="text" class="form-control">
              </div>

              <div class="form-group">
                <label>Photo</label>
                <br>
                <img style="max-width:100%;" id="slider-photo-preview" src="" alt="">
                <br>
                <br>
                <input style="display:none;" name="photo" type="file" class="form-control" id="slider-photo">
                <label for="slider-photo">
                  <img style="width:60px; cursor:pointer;" src="https://icon-library.com/images/image-icon/image-icon-2.jpg" alt="">
                </label>
              </div>
              <hr>
              <div class="form-group slider-btn-opt">

                <a id="add-new-slider-button" class="btn btn-sm btn-info" href="#">Add slider button</a>
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
          <div class="card-header">
            <h4 class="card-title">Edit Slide</h4>
          </div>
          <div class="card-body">
            @include('validate')
            <form action="{{ route('slider.update', $slider -> id ) }}" method="POST" enctype="multipart/form-data">
              @csrf
              @method('PUT')
              <div class="form-group">
                <label>Hall Name:</label>
                <input name="hall" type="text" value="{{ $slider -> hall }}" class="form-control">
              </div>

              <div class="form-group">
                <label>Room No:</label>
                <input name="room" value="{{ $slider -> room }}" type="text" class="form-control">
              </div>

              <div class="form-group">
                <label>Seat No:</label>
                <input name="seat" value="{{ $slider -> seat }}" type="text" class="form-control">
              </div>

              <div class="form-group">
                <label>For Gender:</label>
                <input name="gender" value="{{ $slider -> gender }}" type="text" class="form-control">
              </div>

              <div class="form-group">
                <label>Photo</label>
                <br>
                <img style="max-width:100%;" id="slider-photo-preview" src="{{ url('storage/sliders/' . $slider -> photo) }}" alt="">
                <br>
                <input style="display:none;" name="photo" type="file" class="form-control" id="slider-photo">
                <label for="slider-photo">
                  <img style="width: 80px; cursor: pointer;" src="https://icon-library.com/images/image-icon/image-icon-2.jpg" alt="">
                </label>
              </div>
              <hr>
              <div class="form-group slider-btn-opt">
                @php $i = 1; @endphp
                @foreach ( json_decode($slider -> btns) as $btn)
                  <div class="btn-opt-area">
                    <span>Button:{{ $i }}</span>
                    <span class="badge badge-danger remove-btn" style="margin-left:300px;cursor:pointer;">remove</span>
                    <input class="form-control" name="btn_title[]" value="{{ $btn -> btn_title }}" type="text" placeholder="Button Title">
                    <input class="form-control" value="{{ $btn -> btn_link }}" name="btn_link[]" type="text" placeholder="Button Link">
                    <select class="form-control" name="btn_type[]">
                      <option @if( $btn -> btn_type === 'btn-light-out' ) selected @endif value="btn-light-out"> default </option>
                      <option @if( $btn -> btn_type === 'btn-color btn-full' ) selected @endif  value="btn-color btn-full"> Red </option>
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
