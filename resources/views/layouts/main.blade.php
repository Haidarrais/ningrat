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
        <link rel="stylesheet" href="{{ asset('vendor/select2/css/select2.min.css') }}">
        <style>
            button:disabled {
                cursor: not-allowed;
                pointer-events: all !important;
            }
            ul.timeline {
                list-style-type: none;
                position: relative;
            }
            ul.timeline:before {
                content: ' ';
                background: #d4d9df;
                display: inline-block;
                position: absolute;
                left: 29px;
                width: 2px;
                height: 100%;
                z-index: 400;
            }
            ul.timeline > li {
                margin: 20px 0;
                padding-left: 50px;
            }
            ul.timeline > li:before {
                content: ' ';
                background: white;
                display: inline-block;
                position: absolute;
                border-radius: 50%;
                border: 3px solid green;
                left: 20px;
                width: 20px;
                height: 20px;
                z-index: 400;
            }
        </style>
        @livewireScripts
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10">
        </script>
        @yield('modal')
        <x-livewire-alert::scripts />
    </body>
</html>
