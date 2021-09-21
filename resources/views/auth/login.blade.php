@extends('layouts.auth')

@section('title', 'LOGIN')

@section('content')
<div class="row">
    <div class="col-12 col-sm-8 offset-sm-2 col-md-6 offset-md-3 col-lg-6 offset-lg-3 col-xl-4 offset-xl-4">
        <div class="login-brand">
            <img src="{{ asset('assets-dashboard/img/stisla-fill.svg') }}" alt="logo" width="100"
                class="shadow-light rounded-circle">
        </div>

        <div class="card card-primary">
            <div class="card-header">
                <h4>Login</h4>
            </div>


            <div class="card-body">
                @if (request()->session()->has('status'))
                    <div class="alert alert-danger">
                        {{ request()->session()->get('status') }}
                    </div>
                @endif
                <form method="POST" action="{{ route('login') }}" id="form-login" class="needs-validation" novalidate="" autocomplete="off">
                    @csrf
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" tabindex="1" value="{{ old('email') ?? 'superadmin@gmail.com' }}" required autofocus>
                        @error('email')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <div class="d-block">
                            <label for="password" class="control-label">Password</label>
                            <div class="float-right">
                                <a href="auth-forgot-password.html" class="text-small">
                                    Forgot Password?
                                </a>
                            </div>
                        </div>
                        <input id="password" type="password" class="form-control @error('email') is-invalid @enderror" name="password" tabindex="2" value="123456" required>
                    </div>

                    <div class="form-group">
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" name="remember" class="custom-control-input" tabindex="3"
                                id="remember-me">
                            <label class="custom-control-label" for="remember-me">Remember Me</label>
                        </div>
                    </div>

                    <div class="form-group">
                        <button type="submit" class="btn btn-primary btn-lg btn-block" tabindex="4">
                            Login
                        </button>
                    </div>
                </form>

            </div>
        </div>
        <div class="mt-5 text-muted text-center">
            Don't have an account? <a href="{{ route('register') }}">Create One</a>
        </div>
        <div class="simple-footer">
            Copyright &copy; ALFATH {{ date('Y') }}
        </div>
    </div>
</div>
@endsection

@section('js')
<script src="{{ asset('vendor/loadingoverlay.min.js') }}"></script>
<script>
    $("#form-login").on('submit', async e => {
        $.LoadingOverlay('show')
        e.preventDefault()
        await $axios.post(`{{ route('login') }}`, $("#form-login").serialize())
            .then(({data}) => {
                $swal.fire('Berhasil', 'Login Sukses', 'success')
                $.LoadingOverlay('hide')
                window.location.replace(`{{ route('dashboard') }}`)
            })
            .catch(err => {
                if(err.response.status == 422) {
                    let message = err.response.data.errors
                    let teks_error = ''
                    $.each(message, (i, e) => {
                        if(e.length > 1) {
                            $.each(e, (id, el) => {
                                teks_error += `<p>${el}</p>`
                            })
                        } else {
                            teks_error += `<p>${e}</>`
                        }
                    })
                    $swal.fire({
                        icon: 'error',
                        title: err.response.data.message,
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
                $.LoadingOverlay('hide')
            })
    })
</script>
@endsection
