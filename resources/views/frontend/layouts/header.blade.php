{{-- frontend/layouts/header.blade.php --}}
<header class="header sticky-top">
  <div class="container">
    <div class="header_contact d-flex justify-content-between align-items-center py-2">
      <div class="c-info">
        <i class="fa fa-envelope me-2"></i>
        <a href="mailto:admin@cityuniversity.edu.bd">admin@cityuniversity.edu.bd</a>
      </div>
      <div class="c_box">
        <i class="fas fa-phone-alt me-2"></i>
        <a href="tel:+8801819813111" class="c_text">+880-1819813111</a>
      </div>
    </div>
  </div>
</header>

<!-- Navbar Start -->
<nav class="navbar navbar-expand-lg navbar-light bg-light sticky-top shadow-sm">
  <div class="container">
    <a href="{{ route('home.page') }}" class="navbar-brand">
      <img src="{{ url('frontend/assets/img/logo.png') }}" alt="CU Logo" width="90%" height="auto" class="logo-offset">
    </a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarCollapse">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarCollapse">
      <ul class="navbar-nav ms-auto">
        <li><a class="nav-link" href="#facility">Facilities</a></li>
        <li><a class="nav-link" href="#inRoomFacility">In-Room Facilities</a></li>
        <li><a class="nav-link" href="#hallInfo">Halls</a></li>
        <li><a class="nav-link" href="#hallLife">Hall Life</a></li>
        <li><a class="nav-link" href="#faq">FAQ</a></li>
        <li><a class="nav-link" href="#testimonial">Testimonial</a></li>
        <li><a class="nav-link" href="#contact">Contact</a></li>

        <li class="nav-item">
          <a href="{{ route('notices.index') }}" class="nav-link" target="_blank">Notice</a>
        </li>
      </ul>

      <a href="{{ route('login') }}" class="btn btn-primary rounded-pill ms-lg-3 login-offset" target="_blank" title="Login to access your portal">
        Login
      </a>
    </div>
  </div>
</nav>
<!-- Navbar End -->
