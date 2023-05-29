<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="description" content="{{ __('Productify is a production management system build to simplify production or manufacturing process. Productify is lightweight, secure and fast and based on laravel.') }}">
        <meta name="keywords" content="{{ __('Productify,Production management system,Manufacturing system, Inventory system, Stock management, Workshop management, Row material management') }}">
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
        <link href="https://fonts.googleapis.com/css?family=Sen:400,700,800&display=swap" rel="stylesheet">
        <!-- Main css -->
        <link rel="stylesheet" type="text/css" href=" {{ asset('css/app.css') }} ">
        <!-- Auth pages css -->
        <link rel="stylesheet" type="text/css" href="{{ asset('css/auth.css') }}">

    </head>
    <body class="hold-transition">
        <div class="wrapper" id="app">
            @yield('content')
        </div>

        <!-- REQUIRED SCRIPTS -->
        <script src="{{ asset('js/app.js') }}"></script>
    </body>
</html>
