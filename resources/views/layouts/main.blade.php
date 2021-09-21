<!doctype html>
<html class="no-js" lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="x-ua-compatible" content="ie=edge">
        <title>Naturecircle - Premium eCommerce Template</title>
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <!-- Favicon -->
        <link rel="shortcut icon" type="image/x-icon" href="{{ asset('assets/img/favicon.png') }}">
        @livewireStyles
        <!-- All css here -->
        @yield('css')
        <link rel="stylesheet" href="{{ asset('assets/css/bootstrap.min.css') }}">
        <link rel="stylesheet" href="{{ asset('assets/css/font-awesome.min.css') }}">
        <link rel="stylesheet" href="{{ asset('assets/css/ie7.css') }}">
        <link rel="stylesheet" href="{{ asset('assets/css/plugins.css') }}">
        <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
        <script src="{{ asset('assets/js/vendor/modernizr-3.5.0.min.js') }}"></script>
    </head>
    <body>

        <livewire:header-component />
        {{$slot ?? ''}}
        {{-- @include('partials.main.hero')
        @include('partials.main.category')
        @include('partials.main.product')
        @include('partials.main.banner')
        @include('partials.main.featured')
        @include('partials.main.testimonial')
        @include('partials.main.quickview') --}}
        @yield('content')
        @include('partials.main.footer')
        <!-- All js here -->
        <script src="{{ asset('assets/js/vendor/jquery-3.2.1.min.js') }}"></script>
        <script src="{{ asset('assets/js/popper.min.js') }}"></script>
        <script src="{{ asset('assets/js/bootstrap.min.js') }}"></script>
        <script src="{{ asset('assets/js/plugins.js') }}"></script>
        <script src="{{ asset('assets/js/ajax-mail.js') }}"></script>
        <script src="{{ asset('assets/js/main.js') }}"></script>
        @livewireScripts
        @yield('modal')
    </body>
</html>
