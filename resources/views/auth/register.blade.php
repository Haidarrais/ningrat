@extends('layouts.auth')

@section('title', 'REGISTER')

@section('content')
<div class="row">
    <div class="col-12 col-sm-10 offset-sm-1 col-md-8 offset-md-2 col-lg-8 offset-lg-2 col-xl-8 offset-xl-2">
        <div class="login-brand">
            <img src="{{ asset('assets-dashboard/img/stisla-fill.svg') }}" alt="logo" width="100" class="shadow-light rounded-circle">
        </div>

        <div class="card card-primary">
            <div class="card-header">
                <h4>Register</h4>
            </div>

            <div class="card-body">
                <form method="POST" action="{{ route('register') }}">
                    @csrf
                    <div class="row">
                        <div class="form-group col-6">
                            <label for="name">Nama</label>
                            <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" autofocus>
                            @error('name')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="form-group col-6">
                            <label for="email">Email</label>
                            <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}">
                            @error('email')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="form-group col-6">
                            <label for="password" class="d-block">Password</label>
                            <input id="password" type="password" class="form-control @error('password') is-invalid @enderror pwstrength" data-indicator="pwindicator" name="password">
                            <div id="pwindicator" class="pwindicator">
                                <div class="bar"></div>
                                <div class="label"></div>
                            </div>
                            @error('password')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="form-group col-6">
                            <label for="password2" class="d-block">Password Confirmation</label>
                            <input id="password2" type="password" class="form-control" name="password_confirmation">
                        </div>
                    </div>

                    <div class="row">
                        <div class="form-group col-6">
                            <label for="kode_referal">Kode Referal <small class="text-danger">Optional</small></label>
                            <input id="kode_referal" type="text" class="form-control @error('kode_referal') is-invalid @enderror" name="kode_referal" value="{{ old('kode_referal') }}">
                            @error('kode_referal')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="form-group col-6">
                            <label for="level">Level</label>
                            <select name="role" id="level" class="form-control @error('role') is-invalid @enderror">
                                <option value="">== Pilih Level ==</option>
                                @foreach ($role as $value)
                                    <option value="{{ $value->id }}">{{ $value->name }}</option>
                                @endforeach
                            </select>
                            @error('role')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" name="agree" class="custom-control-input" id="agree" required>
                            <label class="custom-control-label" for="agree">I agree with the terms and conditions</label>
                        </div>
                    </div>

                    <div class="form-group">
                        <button type="submit" class="btn btn-primary btn-lg btn-block">
                            Register
                        </button>
                    </div>
                </form>
            </div>
        </div>
        <div class="mt-5 text-muted text-center">
            Already an account? <a href="{{ route('login') }}">Login</a>
        </div>
        <div class="simple-footer">
            Copyright &copy; ALFATH {{ date('Y') }}
        </div>
    </div>
</div>
@endsection
