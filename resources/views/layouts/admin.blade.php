<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    {{-- <style>
        #sideNavStyle {
            height: 100vh;
            overflow-y: auto;

        }
    </style> --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.js"></script>

    {{-- toster --}}
    <link href="https://cdn.jsdelivr.net/npm/toastr@2.1.4/build/toastr.min.css" rel="stylesheet">

    <meta charset="utf-8">
    <meta name="description"
        content="{{ __('Productify is a production management system build to simplify production or manufacturing process. Productify is lightweight, secure and fast and based on laravel.') }}">
    <meta name="keywords"
        content="{{ __('Productify, Production management system, Manufacturing system, Inventory system, Stock management, Workshop management, Row material management, Garments System, Food and Beverage, Furniture Companies') }}">
    <meta name="author" content="{{ __('Codeshaper') }}">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- Title -->
    <title>{{ $settings['companyTagline'] }}</title>
    <!-- favicon -->
    <link rel="apple-touch-icon" sizes="180x180" href="{{ $settings['favicon'] }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ $settings['favicon'] }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ $settings['favicon'] }}">
    <link rel="manifest" href="{{ asset('/site.webmanifest') }}">
    <!-- Google font -->
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,wght@0,400;0,500;0,700;1,400&display=swap"
        rel="stylesheet">

    @stack('styles')

    <!-- Main css -->
    <link rel="stylesheet" type="text/css" href=" {{ asset('css/app.css') }} ">
    {{-- data table css --}}
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.4/css/dataTables.bootstrap4.min.css">
    <!-- Admin panel css -->
    <link rel="stylesheet" type="text/css" href=" {{ asset('css/main.css') }} ">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="{{ asset('js/app.js') }} "></script>
    {{-- toster --}}
    <script src="
    https://cdn.jsdelivr.net/npm/toastr@2.1.4/build/toastr.min.js
    "></script>
    @yield('extra-style')

</head>

<body
    class="hold-transition sidebar-mini
      @if (session('isDark')) dark-mode @endif
      @if (session('isNavFixed')) layout-navbar-fixed @endif
      @if (session('isSidebarCollapsed')) sidebar-collapse @endif
      @if (session('isSidebarFixed')) layout-fixed @endif">
    <div class="wrapper" id="app">
        <!-- Navbar -->
        @include('admin.components.top_nav')
        <!-- /.navbar -->

        <!-- Main Sidebar Container -->
        @include('admin.components.side_nav')
        <!-- /.Main Sidebar Container -->

        <!-- page content -->
        <div class="content-wrapper">
            @yield('content')
        </div>
        <!-- /.page content-->

        <!-- Main Footer -->
        @include('admin.components.footer')
        <!--/. Main Footer -->
    </div>
    <!-- ./wrapper -->


    @include('admin.components.sidebar_settings')

    @stack('scripts')

    <!-- REQUIRED SCRIPTS -->
    {{-- <script src="https://code.jquery.com/jquery-3.7.0.min.js" integrity="sha256-2Pmvv0kuTBOenSvLm6bvfBSSHrUJ+3A7x6P5Ebd07/g=" crossorigin="anonymous"></script> --}}
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    {{-- <script src="https://cdn.datatables.net/1.11.4/js/dataTables.   4.min.js"></script> --}}
    <script src="{{ asset('js/main.js') }} "></script>
    <script src="{{ asset('js/sidebar_control.js') }} "></script>
    @yield('extra-script')


    <!-- Language script -->

</body>

</html>
