<div>
        <!-- Hero Area Start -->
        {{-- <div class="ht-hero-section fix" wire:ignore>
            <div class="ht-hero-slider">
                @foreach ($carousel as $crsl)
                    <div class="ht-single-slide">
                        <img src="{{ asset('uploads/contents/'. $crsl->image) }}" alt="" class="w-100">
                        <div class="ht-hero-content-one container">
                        </div>
                    </div>
                @endforeach
            </div>
        </div> --}}
        <div id="carouselExampleIndicators" class="carousel slide" data-ride="carousel">
            <ol class="carousel-indicators">
                @foreach ($carousel as $key => $crsl)
                    <li data-target="#carouselExampleIndicators" data-slide-to="{{$key}}" @if($key==0)class="active"@endif></li>
                @endforeach
                </ol>
                <div class="carousel-inner">
                    @foreach ($carousel as $key => $crsl)
                        <div class="carousel-item @if($key==0)active @endif">
                            <img class="d-block w-100" src="{{ asset('uploads/contents/'. $crsl->image) }}" alt="$crsl->image">
                        </div>
                    @endforeach
                </div>
                <a class="carousel-control-prev" href="#carouselExampleIndicators" role="button" data-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="sr-only">Previous</span>
                </a>
                <a class="carousel-control-next" href="#carouselExampleIndicators" role="button" data-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    <span class="sr-only">Next</span>
                </a>
          </div>
        <!-- Hero Area End -->
        {{-- <!-- Food Categry Area Start -->
        <div class="food-category-area pt-105 pb-70">
            <div class="container text-center">
                <div class="section-title-img">
                    <img src="assets/img/logo/logo.png" alt="">
                    <p>dibawah ini merupakan kategori produk</p>
                </div>
            </div>
            <div class="container">
                <div class="ht-food-slider row">
                    @foreach ($categories as $category )
                        <div class="col text-center">
                            <div class="single-food-category">
                                <a href="shop.html" class="food-cat-img"><img src="assets/img/icon/tea.png" alt=""></a>
                                <img src="assets/img/icon/border.png" alt="">
                                <h4><a href="shop.html">{{$category->cname}}</a></h4>
                                <span>{{$category->count}}</span>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
        <!-- Food Categry Area End --> --}}
        <!-- Shop Banner Area Start -->
        <div class="shop-banner-area pt-60 pb-60">
            <div class="container">
                <div class="row">
                    @if (!$banner->isEmpty())
                    @foreach ($banner as $bnr)
                        <div class="col-md-6">
                            <div class="shop-banner-img">
                                <a><img src="{{ asset('uploads/contents/'. $bnr->image) }}" alt="Banner" wire:click="goBlog({{$bnr->id}})"></a>
                            </div>
                        </div>
                    @endforeach
                    @else
                    <div class="col-md-6">
                        <div class="shop-banner-img">
                            <a href="#"><img src="assets/img/banner/2.jpg" alt=""></a>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="shop-banner-img">
                            <a href="#"><img src="assets/img/banner/3.jpg" alt=""></a>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
        <!-- Shop Banner Area End -->
        <!-- Protuct Area Start -->
        <div class="product-area bg-1 pt-110">
            <div class="container">
                <div class="section-title text-center">
                    <div class="section-img d-flex justify-content-center">
                        <img src="{{ asset('assets/img/logo/logobig.png') }}" alt="Ningrat">
                    </div>
                    <h2><span>Our </span>exclusive products</h2>
                </div>
            </div>
            <div class="container">
                <div class="product-tab-list nav" role="tablist">
                    @foreach ($cat as $key => $cat1 )
                        @if(count($cat1->product)>0)
                        <a @if ($key === 0) class="active" @endif href="#tab{{$key+1}}" data-toggle="tab" role="tab" aria-selected="false" aria-controls="tab{{$key+1}}">{{$cat1->name}}</a>
                        @endif
                    @endforeach
                </div>
                <div class="tab-content text-center">
                    @foreach ($cat as $key => $cat2)
                    @if(count($cat2->product)>0)
                        <div @if ($key === 0) class="tab-pane active show fade" @else class="tab-pane fade" @endif id="tab{{$key+1}}" role="tabpanel">
                            <div class="product-carousel">
                                @foreach ($product as $key => $prod)
                                    @if ($cat2->id === $prod->category_id)
                                        @php
                                            $img = $prod->picture->first()->image ?? '1.jpg';
                                        @endphp
                                        <div class="custom-col">
                                            <div class="single-product-item">
                                                <div class="product-image">
                                                    <a>
                                                        <img src="{{ asset('upload/product/' . $img)}}" alt="">
                                                    </a>
                                                    <div class="product-hover">
                                                        @if (auth()->user() && auth()->user()->isCustomer())
                                                            <a href="{{route('member.showc')}}" class="p-cart-btn" title="Menuju halaman pilihan member">Go Shopping</a>
                                                        @elseif (auth()->user() && auth()->user()->isReseller())
                                                            <a href="{{route('member.showr')}}" class="p-cart-btn" title="Menuju halaman pilihan member">Go Shopping</a>
                                                        @elseif(!Auth::check())
                                                            <ul class="hover-icon-list">
                                                                <li>
                                                                    <a href="{{route('login')}}" title="Harap login terlebih dahulu"><i class="icon icon-FullShoppingCart"></i></a>
                                                                </li>
                                                            </ul>
                                                            <a href="{{route('show.prod.all')}}" class="p-cart-btn" title="Lihat daftar produk">Lihat daftar produk</a>
                                                        @endif
                                                    </div>
                                                </div>
                                                <div class="product-text">
                                                    <div class="product-rating">
                                                        <br>
                                                    </div>
                                                    <h5><a href="shop.html">{{$prod->name}}</a></h5>
                                                    <div class="pro-price">
                                                        <span class="new-price">Rp.{{ number_format($prod->price) }}</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                        @endif
                    @endforeach
                </div>
            </div>
        </div>
        @if (count($testi) >= 4)
        <!-- Testimonial Area Start -->
        <div class="testimonial-area pt-50">
            <div class="container">
                <div class="testimonial-slider-wrapper">
                    <div class="text-carousel text-center">
                        @foreach ($testi as $testi_text)
                        <div class="slider-text">
                            <span class="testi-quote">
                                <img src="assets/img/icon/quote.png" alt="">
                            </span>
                            <p>{{$testi_text->word}}</p>
                        </div>
                        @endforeach
                    </div>
                    <div class="image-carousel">
                        @foreach ($testi as $testi_image)
                        <div class="testi-img">
                            <img src="{{ asset('uploads/contents/'. $testi_image->image) }}" alt="" style="width: 120px; height:120px;">
                            <h4>{{$testi_image->name}}</h4>
                            <h4 style="font-weight: 0.1;margin-top:-20px;"><small>({{$testi_image->actor}})</small></h4>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
        @endif 
</div>
