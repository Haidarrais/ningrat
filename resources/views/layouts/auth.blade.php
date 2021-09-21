<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
    <title>@yield('title', 'AUTH')</title>

    <!-- General CSS Files -->
    <link rel="stylesheet" href="{{ asset('vendor/bootstrap/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.2/css/all.css"
        integrity="sha384-fnmOCqbTlWIlj8LyTjo7mOUStjsKC4pOpQbqyi7RrhN7udi9RwhKkMHpvLbHG9Sr" crossorigin="anonymous">

    <!-- Template CSS -->
    <link rel="stylesheet" href="{{ asset('assets-dashboard/css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('assets-dashboard/css/components.css') }}">
</head>

<body>
    <div id="app">
        <section class="section">
            <div class="container mt-5">
                @yield('content')
            </div>
        </section>
    </div>

    <!-- General JS Scripts -->
    <script src="{{ asset('vendor/jquery/jquery-3.3.1.min.js') }}"></script>
    <script src="{{ asset('js/app.js') }}"></script>
    <script src="{{ asset('vendor/popper.min.js') }}"></script>
    <script src="{{ asset('vendor/bootstrap/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('vendor/jquery.nicescroll.min.js') }}"></script>
    <script src="{{ asset('vendor/moment.min.js') }}"></script>
    <script src="{{ asset('assets-dashboard/js/stisla.js') }}"></script>

    <!-- JS Libraies -->

    <!-- Template JS File -->
    <script src="{{ asset('assets-dashboard/js/scripts.js') }}"></script>
    <script src="{{ asset('assets-dashboard/js/custom.js') }}"></script>
    @yield('js')

    <!-- Page Specific JS File -->
</body>

</html>
