<!DOCTYPE html>
<html lang="en">


<head>
  <title>Comet | Creative Multi-Purpose HTML Template</title>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <link rel="stylesheet" href="{{ url('frontend/css/bundle.css') }}">
  <link rel="stylesheet" href="{{ url('frontend/css/style.css') }}">
  <link href="https://fonts.googleapis.com/css?family=Montserrat:400,700" rel="stylesheet" type="text/css">
  <link href="https://fonts.googleapis.com/css?family=Raleway:300,400,500" rel="stylesheet" type="text/css">
  <link href="https://fonts.googleapis.com/css?family=Halant:300,400" rel="stylesheet" type="text/css">
  <!--[if lt IE 9]>
      <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
      <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
    <![endif]-->
  <script>
    (function(i, s, o, g, r, a, m) {
      i['GoogleAnalyticsObject'] = r;
      i[r] = i[r] || function() {
        (i[r].q = i[r].q || []).push(arguments)
      }, i[r].l = 1 * new Date();
      a = s.createElement(o),
        m = s.getElementsByTagName(o)[0];
      a.async = 1;
      a.src = g;
      m.parentNode.insertBefore(a, m)
    })(window, document, 'script', '../../../www.google-analytics.com/analytics.js', 'ga');
    ga('create', 'UA-46276885-13', 'auto');
    ga('send', 'pageview');
  </script>
</head>

<body>

  <!-- Preloader-->
  <div id="loader">
    <div class="centrize">
      <div class="v-center">
        <div id="mask">
          <span></span>
          <span></span>
          <span></span>
          <span></span>
          <span></span>
        </div>
      </div>
    </div>
  </div>
  <!-- End Preloader-->

  <section id="home">
    <div class="container">
      <div class="row">
        @foreach ($seats as $seat)
        <a href="{{ route('hall-booking.booking',['seat' => $seat->id]) }}">
          <div class="col-lg-2 text-center" style="border: 1px solid #666; padding:5px; border-radius:4px;margin:2px">
            <img src="https://i.pinimg.com/736x/b4/c5/80/b4c5806daa3794192c03f523e54f51ed.jpg" style="border-radius:5px" alt="img" width="100%" height="60px">
            <h6>{{ $seat->room->hall->name }}</h6>
            {{ $seat->room->name. '-' . $seat->name }}
          </div>
        </a>
        @endforeach
      </div>
    </div>
    <!-- Home Slider-->
    <div id="home-slider" class="flexslider">
      <ul class="slides">

        @php
        $sliders = App\Models\Slider::latest()->where('status', true)->where('trash', false)->get();
        @endphp

        @foreach ($sliders as $slide)
        <li>
          <img src="{{url('storage/sliders/' . $slide->photo)}}" alt="">
          <div class="slide-wrap">
            <div class="slide-content">
              <div class="container">
                <h1>{{$slide->hall}}<span class="red-dot"></span></h1>
                <i>for {{$slide->gender}}</i>
                <h6>Room No: {{ $slide->room}}, Seat No: {{ $slide->seat }}</h6>
                <p>

                  @foreach (json_decode($slide->btns) as $btn)
                  <a href="{{$btn->btn_link}}" class="btn {{$btn->btn_type}}">{{$btn->btn_title}}</a>
                  @endforeach

                </p>
              </div>
            </div>
          </div>
        </li>
        @endforeach


      </ul>
    </div>
    <!-- End Home Slider-->
  </section>
  <!-- End Home Section-->

  <script type="text/javascript" src="{{ url('frontend/js/jquery.js') }}"></script>
  <script type="text/javascript" src="{{ url('frontend/js/bundle.js') }}"></script>
  <script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?v=3.exp"></script>
  <script type="text/javascript" src="{{ url('frontend/js/main.js') }}"></script>
</body>


</html>