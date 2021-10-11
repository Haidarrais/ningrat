<div>
    <!-- Header Area Start -->
            <header class="header-area header-sticky">
                <div class="header-container">
                    <div class="row">
                        <div class="col-lg-5 display-none-md display-none-xs">
                            <div class="ht-main-menu">
                                <nav>
                                    <ul>
                                        <li @if ((request()->route()->uri) == '/')class="active" @endif><a href="/">home</i></a>
                                        </li>
                                    </ul>
                                </nav>
                            </div>
                        </div>
                        <div class="col-lg-2 col-sm-4">
                            <div class="logo text-center">
                                <a href="index.html"><img src="{{ asset('assets/img/logo/logo.png') }}" alt="NatureCircle"></a>
                            </div>
                        </div>
                        <div class="col-lg-5 col-sm-8">
                            <div class="header-content d-flex justify-content-end">
                                <div>
                                    @if (session()->has('message'))
                                        <div class="alert alert-danger">
                                            <strong>Gagal </strong>
                                            {{ session('message') }}
                                        </div>
                                    @elseif (Session::has('success_message'))
                                        <div class="alert alert-success">
                                            <strong>Sukses item </strong>{{Session::get('success_message')}}
                                        </div>
                                    @endif
                                </div>
                                <div class="search-wrapper">
                                    <a href="#"><span class="icon icon-Search"></span></a>
                                    <form action="#" class="search-form">
                                        <input type="text" placeholder="Search entire store here ...">
                                        <button type="button">Search</button>
                                    </form>
                                </div>
                                <div class="settings-wrapper">
                                    <a href="#"><i class="icon icon-Settings"></i></a>
                                    <div class="settings-content">
                                        <h4>My Account <i class="fa fa-angle-down"></i></h4>
                                        <ul>
                                            @if(Auth::guard('web')->check())
                                                @if (Auth::user()->isCustomer())
                                                <li><a href="{{ route('profile.customer')}}">My Profile</a></li>
                                                @else
                                                <li><a href="{{ route('dashboard')}}">Dashboard</a></li>
                                                <li><a href="{{ route('profile.reseller')}}">My Profile</a></li>
                                                @endif
                                                <li>
                                                    <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="text-danger">
                                                        Logout
                                                    </a>
                                                </li>
                                                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                                    @csrf
                                                </form>
                                            @else
                                                <li><a href="#" class="modal-view button" data-toggle="modal" data-target="#register_box">Register</a></li>
                                                <li><a href="#" class="modal-view button" data-toggle="modal" data-target="#login_box">login</a></li>
                                            @endif
                                        </ul>
                                    </div>
                                </div>
                                    <div class="cart-wrapper">
                                        <a href="#">
                                            <i class="icon icon-FullShoppingCart"></i>
                                            <span>{{Cart::content()->count()}}</span>
                                        </a>
                                        <div class="cart-item-wrapper">
                                            @if (Cart::count() >0)
                                                @foreach (Cart::content() as $item )
                                                    <div class="single-cart-item">
                                                        <div class="cart-img">
                                                            @if (Auth::user()->isCustomer())
                                                                <a href="{{ route('cart.customer')}}"><img src="{{asset('upload/product/'. $item->options->stock->product->picture->first()->image)}}" alt=""></a>
                                                            @else
                                                                <a href="{{ route('cart.reseller')}}"><img src="{{asset('upload/product/'. $item->options->stock->product->picture->first()->image)}}" alt=""></a>
                                                            @endif
                                                        </div>
                                                        <div class="cart-text-btn">
                                                            <div class="cart-text">
                                                                <h5><a href="/cart">{{$item->name}}</a></h5>
                                                                <span class="cart-qty">{{$item->qty}}</span>
                                                                <span class="cart-price">{{$item->price}}</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            @else
                                                <div class="single-cart-item">No item added</div>
                                            @endif
                                            <div class="cart-price-total">
                                                <div class="cart-price-info d-flex justify-content-between">
                                                    <span>Sub-Total :</span>
                                                    <span>{{Cart::subtotal()}}</span>
                                                </div>
                                                {{-- <div class="cart-price-info d-flex justify-content-between">
                                                    <span>Tax (10%) :</span>
                                                    <span></span>
                                                </div>
                                                <div class="cart-price-info d-flex justify-content-between">
                                                    <span>VAT (20%) :</span>
                                                    <span>$27.00</span>
                                                </div> --}}
                                                <div class="cart-price-info d-flex justify-content-between">
                                                    <span>Total :</span>
                                                    <span>{{Cart::subtotal()}}</span>
                                                </div>
                                            </div>
                                            @if (Cart::content()->count() > 0)
                                                <div class="cart-links">
                                                    @if (Auth::user()->isCustomer())
                                                    <a href="{{route('cart.customer')}}">View cart</a>
                                                    <a href="{{route('checkout.customer')}}">Checkout</a>
                                                    @else
                                                    <a href="{{route('cart.reseller')}}">View cart</a>
                                                    <a href="{{route('checkout.reseller')}}">Checkout</a>
                                                    @endif
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Header Area End -->
                <!-- Mobile Menu Area Start -->
                <div class="mobile-menu-area">
                    <div class="mobile-menu container">
                        <nav id="mobile-menu-active">
                            <ul>
                                <li @if ((request()->route()->uri) == '/')class="active" @endif><a href="/">home</i></a>
                                </li>
                            </ul>
                        </nav>
                    </div>
                </div>
                <!-- Mobile Menu Area End -->
                <!--Start of Login Form-->
                <div class="modal fade" id="login_box" tabindex="-1" role="dialog">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true"><i class="fa fa-close"></i></span></button>
                            </div>
                            <div class="modal-body">
                                <div class="form-pop-up-content">
                                    <h2>Login to your account</h2>
                                    <form method="POST" action="{{ route('login') }}" class="needs-validation" novalidate="" autocomplete="off">
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
                        </div>
                    </div>
                </div>
                <!--End of Login Form-->
                <!--Start of Register Form-->
                <div class="modal fade" id="register_box" tabindex="-1" role="dialog">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true"><i class="fa fa-close"></i></span></button>
                            </div>
                            <div class="modal-body">
                                <div class="form-pop-up-content">
                                    <h2>Sign Up</h2>
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
                        </div>
                    </div>
                </div>
                <!--End of Register Form-->
            </header>
            <!-- Header Area End -->
    </div>
