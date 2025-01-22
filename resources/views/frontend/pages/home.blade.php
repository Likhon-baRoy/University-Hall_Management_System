@extends('frontend.layouts.app')
@section('title', 'Homepage')

@section('main-section')

  <!-- Carousel Start -->
  <div class="container-fluid p-0 mb-5">
    <div class="owl-carousel header-carousel position-relative">
      @forelse($sliders as $slider)
        <div class="owl-carousel-item position-relative">
          <img class="img-fluid w-100" src="{{ url('storage/image/slider/' . $slider->photo) }}"
               alt="{{ $slider->title }}" style="max-height: 600px; object-fit: cover;">
          <div class="position-absolute top-0 start-0 w-100 h-100 d-flex align-items-center"
               style="background: rgba(24, 29, 56, .7);">
            <div class="container">
              <div class="row justify-content-center">
                <div class="col-lg-8 text-center">
                  @if($slider->subtitle)
                    <h5 class="text-primary text-uppercase mb-3 animated fadeInDown">
                      {{ $slider->subtitle }}
                    </h5>
                  @endif
                  <h1 class="display-4 text-white animated fadeInDown mb-4">
                    {{ $slider->title }}
                  </h1>
                  @if($slider->description)
                    <p class="fs-5 text-white mb-4 animated fadeInUp">
                      {{ $slider->description }}
                    </p>
                  @endif
                  @if($slider->btns)
                    <div class="slider-buttons animated fadeInUp">
                      @foreach(json_decode($slider->btns) as $btn)
                        <a href="{{ $btn->btn_link }}"
                           class="{{ $btn->btn_type == 'btn-light-out' ? 'btn btn-light' : 'btn btn-primary' }}
                                 py-2 px-4 me-3 mb-2">
                          {{ $btn->btn_title }}
                        </a>
                      @endforeach
                    </div>
                  @endif
                </div>
              </div>
            </div>
          </div>
        </div>
      @empty
        <div class="owl-carousel-item position-relative">
          <img class="img-fluid w-100" src="frontend/assets/img/cuadmin.jpg"
               alt="cityuniversityfront" style="max-height: 600px; object-fit: cover;">
          <div class="position-absolute top-0 start-0 w-100 h-100 d-flex align-items-center"
               style="background: rgba(24, 29, 56, .7);">
            <div class="container">
              <div class="row justify-content-center">
                <div class="col-lg-8 text-center">
                  <h5 class="text-primary text-uppercase mb-3 animated fadeInDown">
                    Book Your Seat
                  </h5>
                  <h1 class="display-4 text-white animated fadeInDown mb-4">
                    Creating a culture of excellence
                  </h1>
                  <p class="fs-5 text-white mb-4 animated fadeInUp">
                    City University is committed to the idea of equal opportunity,
                    transparency and non-discrimination.
                  </p>
                  <div class="slider-buttons animated fadeInUp">
                    <a href="" class="btn btn-primary py-2 px-4 me-3 mb-2">Read More</a>
                    <a href="{{ route('hall-booking.index') }}"
                       class="btn btn-light py-2 px-4 mb-2">Book Now</a>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      @endforelse
    </div>
  </div>
  <!-- /Carousel End -->

  @include('frontend.sections.facility')
  @include('frontend.sections.moreFacility')
  @include('frontend.sections.hallInfo')
  @include('frontend.sections.hallLife')
  @include('frontend.sections.hallSuper')
  @include('frontend.sections.inRoomFacility')
  @include('frontend.sections.faq')
  @include('frontend.sections.testimonial')
  @include('frontend.sections.contact')

@endsection
