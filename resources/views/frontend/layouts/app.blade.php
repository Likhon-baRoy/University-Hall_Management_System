<!DOCTYPE html>
<html lang="en">

  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
    <title>@yield('title', 'Title Undefined')</title>

    <!-- Favicon -->
    <link href="frontend/assets/img/favicon.png" rel="icon">

    <!-- Google Web Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
      href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap"
      rel="stylesheet">

    <!-- Icon Font Stylesheet -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Libraries Stylesheet -->
    <link href="frontend/assets/lib/animate/animate.min.css" rel="stylesheet">
    <link href="frontend/assets/lib/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet">

    <!-- Main CSS -->
    <link href="frontend/assets/css/style.css" rel="stylesheet">

    <!-- Customized Bootstrap Stylesheet -->
    <link href="frontend/assets/css/bootstrap.min.css" rel="stylesheet">

    <!--Bootstrap linking-->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
  </head>

  <body>

    @include('frontend.layouts.header')
    @include('frontend.layouts.navbar')

    {{-- main section --}}
    @section('main-section')
    @show

    @include('frontend.layouts.footer')

    <!---------------------------------- JavaScript Libraries -------------------------------------------------->
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
            integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
            crossorigin="anonymous"></script>
    <script src="frontend/assets/lib/wow/wow.min.js"></script>
    <script src="frontend/assets/lib/easing/easing.min.js"></script>
    <script src="frontend/assets/lib/waypoints/waypoints.min.js"></script>
    <script src="frontend/assets/lib/owlcarousel/owl.carousel.min.js"></script>

    <!------------------- Javascript -------------------------->
    <script src="frontend/assets/js/main.js"></script>

    <script>
     function toggleDropdown() {
       const dropdown = document.getElementById('dropdownMenu');
       dropdown.style.display = dropdown.style.display === 'block' ? 'none' : 'block';
     }
     window.onclick = function(event) {
       if (!event.target.matches('.profile-icon')) {
         const dropdown = document.getElementById('dropdownMenu');
         if (dropdown) dropdown.style.display = 'none';
       }
     }
     function hideWelcomeMessage() {
       const message = document.getElementById('welcomeMessage');
       if (message) {
         setTimeout(() => {
           message.classList.add('hidden');
         }, 2000);
       }
     }
     window.onload = hideWelcomeMessage;
    </script>

</body>

</html>
