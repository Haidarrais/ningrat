@extends('layouts.auth')

@section('title', 'REGISTER')

@section('content')
<div class="row">
    <div class="col-12 col-sm-10 offset-sm-1 col-md-8 offset-md-2 col-lg-8 offset-lg-2 col-xl-8 offset-xl-2">
        <div class="login-brand">
            <img src="{{ asset('assets/img/logo/logo.png') }}" alt="logo" width="100" class="shadow-light">
        </div>

        <div class="card card-primary">
            <div class="card-header">
                <h4>Register</h4>
            </div>

            <div class="card-body">
                <form method="POST" action="{{ route('register') }}" enctype="multipart/form-data">
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
                        <div class="form-group col-6" id="col-referral">
                            <label for="kode_referal">Kode Referal</label>
                            <input id="kode_referal" type="text" class="form-control @error('kode_referal') is-invalid @enderror" name="kode_referal" value="{{ old('kode_referal') }}">
                            @error('kode_referal')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="form-group col-6">
                            <label for="level">Level</label>
                            <select name="role" id="level" class="form-control @error('role') is-invalid @enderror"  value="{{ old('role') }}">
                                <option value="">== Pilih Level ==</option>
                                @foreach ($role as $value)
                                    <option value="{{ $value->id }}">{{ ucfirst($value->name) }}</option>
                                @endforeach
                            </select>
                            @error('role')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    <div class="row" id="form-tambahan-member">
                        <div class="form-group col-6">
                            <label for="ttl">Tempat Tanggal Lahir</label>
                            <input id="ttl" type="date" class="form-control @error('ttl') is-invalid @enderror" name="ttl" value="{{ old('ttl') }}">
                            @error('ttl')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="form-group col-6">
                            <label for="nowhatsapp">No. WhatsApp</label>
                            <input id="nowhatsapp" type="number" class="form-control @error('nowhatsapp') is-invalid @enderror" name="nowhatsapp" value="{{ old('nowhatsapp') }}" placeholder="08123456789">
                            @error('nowhatsapp')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="form-group col-6">
                            <label for="instagram">Instagram</label>
                            <input id="instagram" type="text" class="form-control @error('instagram') is-invalid @enderror" name="instagram" value="{{ old('instagram') }}" placeholder="'@' username">
                            @error('instagram')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="form-group col-6">
                            <label for="facebook">Facebook</label>
                            <input id="facebook" type="text" class="form-control @error('facebook') is-invalid @enderror" name="facebook" value="{{ old('facebook') }}" placeholder="username">
                            @error('facebook')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="form-group col-6">
                            <label for="marketplace">Marketplace</label>
                            <input id="marketplace" type="text" class="form-control @error('marketplace') is-invalid @enderror" name="marketplace" value="{{ old('marketplace') }}" placeholder="Shopee, Tokopedia, dll">
                            @error('marketplace')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="form-group col-6">
                            <label for="mou">File MOU <span class="text-success" id="filedownload"></span></label>
                            <input id="mou" type="file" class="form-control @error('mou') is-invalid @enderror" name="mou" value="{{ old('mou') }}">
                            @error('mou')
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
@section('js')
<script>
    $(document).ready(function(){
        $('#form-tambahan-member').hide();
        $('#col-referral').hide();
    })
    $('#level').on('change', function(){
            console.log($(this).val())
            if ($(this).val() == 2 || $(this).val() == 3 || $(this).val() == 4 || $(this).val() == 5) {
                $('#col-referral').show();
                $('#form-tambahan-member').show();
                $('#filedownload').html(`<a href="{{asset('core/mou/${$(this).val()}.docx')}}">download template</a>`)
            }else{
                $('#col-referral').hide();
                $('#form-tambahan-member').hide();
            }
        })
</script>
@endsection
