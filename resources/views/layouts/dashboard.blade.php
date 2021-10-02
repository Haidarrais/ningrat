<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Projek Ningrat')</title>

    <!-- General CSS Files -->
    <link rel="stylesheet" href="{{ asset('vendor/bootstrap/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('vendor/fontawesome-5.7.2/css/all.min.css') }}">

    <!-- Template CSS -->
    <link rel="stylesheet" href="{{ asset('assets-dashboard/css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('assets-dashboard/css/components.css') }}">
    <link rel="stylesheet" href="{{ asset('vendor/toastr/toastr.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/custom.css') }}">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">

    @yield('css')
</head>

<body>
    <div id="app">
        <div class="main-wrapper">
            <div class="navbar-bg"></div>
            @include('partials.dashboard.navbar')
            @include('partials.dashboard.sidebar')


            <!-- Main Content -->
            <div class="main-content">
                <section class="section">
                    <div class="section-header">
                        <h1>
                            @if (is_numeric(request()->segment(count(request()->segments()))))
                            {{ strtoupper(request()->segment((count(request()->segments()) -1))) }}
                            @else
                            {{ strtoupper(request()->segment(count(request()->segments()))) }}
                            @endif
                            {{ isset($text_dashboard) ? '-' . strtoupper($text_dashboard) : '' }}
                        </h1>
                        <div class="section-header-breadcrumb">
                            @php
                            $path = explode('/', request()->path());
                            @endphp
                            @forelse ($path as $key => $value)
                            @if (is_numeric($value) || strpos($value, '='))
                            @php
                            continue;
                            @endphp
                            @endif
                            @if ($key == 0)
                            <div class="breadcrumb-item active"><a href="{{ route($value) }}">{{ ucfirst($value) }}</a></div>
                            @else
                            <div class="breadcrumb-item">{{ ucfirst($value) }}</div>
                            @endif
                            @empty

                            @endforelse
                        </div>
                    </div>
                    {{-- <div class="section-body">
                    </div> --}}

                    @yield('content')

                </section>
            </div>
            @include('partials.dashboard.footer')
        </div>
    </div>

    @yield('modal')

    <!-- General JS Scripts -->
    <script src="{{ asset('js/app.js') }}"></script>
    <script src="{{ asset('vendor/jquery/jquery-3.3.1.min.js') }}"></script>
    <script src="{{ asset('vendor/popper.min.js') }}"></script>
    <script src="{{ asset('vendor/bootstrap/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('vendor/jquery.nicescroll.min.js') }}"></script>
    <script src="{{ asset('vendor/moment.min.js') }}"></script>
    <script src="{{ asset('assets-dashboard/js/stisla.js') }}"></script>

    <!-- JS Libraies -->
    <script src="{{ asset('vendor/toastr/toastr.min.js') }}"></script>
    <script src="{{ asset('vendor/loadingoverlay.min.js') }}"></script>

    <!-- Template JS File -->
    <script src="{{ asset('assets-dashboard/js/scripts.js') }}"></script>

    <!-- Global JS -->
    <script>
        const BASE_URL = `{{ url('/') }}`
        const URL_NOW = `{{ request()->url() }}`
        const CSRF_TOKEN = document.querySelector('meta[name="csrf-token"]').getAttribute("content")
    </script>

    <!-- Page Specific JS File -->

    <script>
        $(document).ready(function() {
            // Ajax Paginate
            if (window.$paging) {
                $(document).on('click', '.pagination a', function(event) {
                    event.preventDefault()
                    // Jika di url terdapat "page=" maka di pisah
                    let page = $(this).attr('href').split('page=')[1]
                    let l = window.location.href
                    let url = ''
                    // Jika tidak ada keyword / kata kunci yang sedang dicari
                    if (l.includes('?')) {
                        url = l + "&page=" + page
                    } else {
                        url = l + "?page=" + page
                    }

                    refresh_table(url)
                })
            }
        })

        const refresh_table = url => {
            new Promise((resolve, reject) => {
                $("#table_data").LoadingOverlay('show')
                $axios.get(url)
                    .then(({
                        data
                    }) => {
                        $("#table_data").LoadingOverlay('hide')
                        $('#table_data').html(data)
                    })
                    .catch(err => {
                        console.log(err)
                        $("#table_data").LoadingOverlay('hide')
                        $swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: 'Something went wrong!',
                        })
                    })
            })
        }

        const loading = (type, selector = null, options = null) => {
            if (selector) {
                $(selector).LoadingOverlay(type, options)
            } else {
                $.LoadingOverlay(type, options)
            }
        }

        const throwErr = err => {
            if (err.response.status == 422) {
                let message = err.response.data.errors
                let teks_error = ''
                $.each(message, (i, e) => {
                    if (e.length > 1) {
                        $.each(e, (id, el) => {
                            teks_error += `<p>${el}</p>`
                        })
                    } else {
                        teks_error += `<p>${e}</>`
                    }
                })
                $swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    html: teks_error,
                })
            } else {
                let message = err.response.data.message
                $swal.fire({
                    icon: 'error',
                    title: message.head,
                    text: message.body,
                })
            }
        }

        (function($) {
            $.fn.inputFilter = function(inputFilter) {
                return this.on("input keydown keyup mousedown mouseup select contextmenu drop", function() {
                    if (inputFilter(this.value)) {
                        this.oldValue = this.value
                        this.oldSelectionStart = this.selectionStart
                        this.oldSelectionEnd = this.selectionEnd
                    } else if (this.hasOwnProperty("oldValue")) {
                        this.value = this.oldValue
                        this.setSelectionRange(this.oldSelectionStart, this.oldSelectionEnd)
                    } else {
                        this.value = ""
                    }
                })
            }
        }(jQuery))
    </script>

    @yield('js')
</body>

</html>