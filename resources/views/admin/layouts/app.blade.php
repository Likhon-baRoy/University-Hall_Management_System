<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
  <title>@yield('title', 'Title Undefined')</title>

  <!-- Favicon -->
  <link rel="shortcut icon" type="image/x-icon" href="{{ asset('admin/assets/img/favicon.png') }}">

  <!-- Bootstrap CSS -->
  <link rel="stylesheet" href="{{ asset('admin/assets/css/bootstrap.min.css') }}">
  <link rel="stylesheet" type="text/css" href="{{ asset('https://cdn.datatables.net/v/bs4/dt-1.12.1/datatables.min.css') }}"/>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">

  <!-- Fontawesome CSS -->
  <link rel="stylesheet" href="{{ asset('admin/assets/css/font-awesome.min.css') }}">

  <!-- Feathericon CSS -->
  <link rel="stylesheet" href="{{ asset('admin/assets/css/feathericon.min.css') }}">

  <link rel="stylesheet" href="{{ asset('admin/assets/plugins/morris/morris.css') }}">

  <!-- Main CSS -->
  <link rel="stylesheet" href="{{ asset('admin/assets/css/style.css') }}">

  <!-- Custom CSS -->
  @yield('custom-css')

  <!--[if lt IE 9]>
    <script src="admin/assets/js/html5shiv.min.js"></script>
    <script src="admin/assets/js/respond.min.js"></script>
  <![endif]-->
</head>
<body>

  <!-- SVG template for Sidebar submenus -->
  <template id="commit-node-icon">
    <svg aria-hidden="true" focusable="false" class="Octicon-sc-9kayk9-0" viewBox="0 0 16 16" width="16" height="16" fill="currentColor" style="display: inline-block; user-select: none; vertical-align: text-bottom; overflow: visible;">
      <path d="M11.93 8.5a4.002 4.002 0 0 1-7.86 0H.75a.75.75 0 0 1 0-1.5h3.32a4.002 4.002 0 0 1 7.86 0h3.32a.75.75 0 0 1 0 1.5Zm-1.43-.75a2.5 2.5 0 1 0-5 0 2.5 2.5 0 0 0 5 0Z"></path>
    </svg>
  </template>

  <!-- Main Wrapper -->
  <div class="main-wrapper">

    @include('admin.layouts.header')
    @include('admin.layouts.sidebar')

    <!-- Page Wrapper -->
    <div class="page-wrapper">

      <div class="content container-fluid">

        {{-- main section --}}
        @section('main-section')
        @show

      </div>
    </div>
    <!-- /Page Wrapper -->

  </div>
  <!-- /Main Wrapper -->

  @stack('scripts')
  <!-- jQuery -->
  <script src="{{asset('admin/assets/js/jquery-3.2.1.min.js')}}"></script>

  <!-- Bootstrap Core JS -->
  <script src="{{asset('admin/assets/js/popper.min.js')}}"></script>
  <script src="{{asset('admin/assets/js/bootstrap.min.js')}}"></script>

  <!-- Searching JavaScript -->
  <script type="text/javascript" src="{{ asset('https://cdn.datatables.net/v/bs4/dt-1.12.1/datatables.min.js') }}"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js')}}"></script>

  <!-- Slimscroll JS -->
  <script src="{{asset('admin/assets/plugins/slimscroll/jquery.slimscroll.min.js')}}"></script>

  <script src="{{asset('admin/assets/plugins/raphael/raphael.min.js')}}"></script>
  <script src="{{asset('admin/assets/plugins/morris/morris.min.js')}}"></script>
  <script src="{{asset('admin/assets/js/chart.morris.js')}}"></script>

  <!-- Custom JS -->
  @yield('custom-js')
  <script src="{{asset('admin/assets/js/script.js')}}"></script>
  <script src="{{asset('admin/assets/js/notifications.js')}}"></script>
  <script src="{{asset('custom/admin.js')}}"></script>
  <script src="{{asset('custom/notice.js')}}"></script>
</body>
</html>
