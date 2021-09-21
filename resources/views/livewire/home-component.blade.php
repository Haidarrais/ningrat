<div>
            <div class="ht-hero-section fix">
            <div class="ht-hero-slider">
                <!-- Single Slide Start -->
                <div class="ht-single-slide" style="background-image: url(assets/img/slider/4.jpg)">
                    <div class="ht-hero-content-one container">
                    </div>
                </div>
                <!-- Single Slide End -->
                <!-- Single Slide Start -->
                <div class="ht-single-slide" style="background-image: url(assets/img/slider/4.jpg)">
                    <div class="ht-hero-content-one container">

                    </div>
                </div>
                <!-- Single Slide End -->
            </div>
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
                    <div class="col-md-6">
                        <div class="shop-banner-img">
                            <a href="shop.html"><img src="assets/img/banner/2.jpg" alt=""></a>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="shop-banner-img">
                            <a href="shop.html"><img src="assets/img/banner/3.jpg" alt=""></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Shop Banner Area End -->
        <!-- Protuct Area Start -->
        <div class="product-area bg-1 pt-110 pb-80">
            <div class="container">
                <div class="section-title text-center">
                    <div class="section-img d-flex justify-content-center">
                        <img src="assets/img/icon/title.png" alt="">
                    </div>
                    <h2><span>Organic </span>featured products</h2>
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
                     @if(count($cat1->product)>0)
                        <div @if ($key === 0) class="tab-pane active show fade" @else class="tab-pane fade" @endif id="tab{{$key+1}}" role="tabpanel">
                            <div class="product-carousel">
                                @foreach ($product as $key => $prod)
                                    @if ($cat2->id === $prod->category_id)
                                        @php
                                            $mod = $key%2;
                                        @endphp
                                        @if ($mod == 0)
                                        <div class="custom-col">
                                        @endif
                                            <div class="single-product-item">
                                                <div class="product-image">
                                                    <a href="/member">
                                                        <img src="{{ asset('upload/product/' . $prod->image)}}" alt="">
                                                    </a>
                                                    <div class="product-hover">
                                                        @if (auth()->user() && auth()->user()->isCustomer())
                                                            <a href="{{route('member.showc')}}" class="p-cart-btn" title="Menuju halaman pilihan member">Go Shopping</a>
                                                        @elseif (auth()->user() && auth()->user()->isReseller())
                                                            <a href="{{route('member.showr')}}" class="p-cart-btn" title="Menuju halaman pilihan member">Go Shopping</a>
                                                        @elseif(!Auth::check())
                                                            <a href="{{route('login')}}" class="p-cart-btn" title="Harap login terlebih dahulu">Login</a>
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
                                        @if ($mod == 1)
                                        </div>
                                        @endif
                                    @endif
                                @endforeach
                            </div>
                        </div>
                        @endif
                    @endforeach
                </div>
            </div>
        </div>
        <!-- Testimonial Area Start -->
        <div class="testimonial-area pt-110 pb-95">
            <div class="container">
                <div class="testimonial-slider-wrapper">
                    <div class="text-carousel text-center">
                        <div class="slider-text">
                            <span class="testi-quote">
                                <img src="assets/img/icon/quote.png" alt="">
                            </span>
                            <p>This is Photoshops version  of Lorem Ipsum. Proin gravida nibh vel velit.Lorem ipsum dolor sit amet, consectetur adipiscing elit. In molestie augue magna. Pellentesque felis lorem, pulvinar sed ero..</p>
                        </div>
                        <div class="slider-text">
                            <span class="testi-quote">
                                <img src="assets/img/icon/quote.png" alt="">
                            </span>
                            <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Reprehenderit tenetur rerum maiores eos fugit dolores neque eius ex eum quo, quis aspernatur odio accusantium architecto, amet repellat.</p>
                        </div>
                        <div class="slider-text">
                            <span class="testi-quote">
                                <img src="assets/img/icon/quote.png" alt="">
                            </span>
                            <p>Reprehenderit tenetur rerum maiores eos fugit dolores neque eius ex eum quo, quis aspernatur odio accusantium architecto, amet repellat Lorem ipsum dolor sit amet, consectetur adipisicing elit.</p>
                        </div>
                        <div class="slider-text">
                            <span class="testi-quote">
                                <img src="assets/img/icon/quote.png" alt="">
                            </span>
                            <p>This is Photoshops version  of Lorem Ipsum. Proin gravida nibh vel velit.Lorem ipsum dolor sit amet, consectetur adipiscing elit. In molestie augue magna. Pellentesque felis lorem, pulvinar sed ero..</p>
                        </div>
                        <div class="slider-text">
                            <span class="testi-quote">
                                <img src="assets/img/icon/quote.png" alt="">
                            </span>
                            <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Reprehenderit tenetur rerum maiores eos fugit dolores neque eius ex eum quo, quis aspernatur odio accusantium architecto, amet repellat.</p>
                        </div>
                        <div class="slider-text">
                            <span class="testi-quote">
                                <img src="assets/img/icon/quote.png" alt="">
                            </span>
                            <p>Reprehenderit tenetur rerum maiores eos fugit dolores neque eius ex eum quo, quis aspernatur odio accusantium architecto, amet repellat Lorem ipsum dolor sit amet, consectetur adipisicing elit.</p>
                        </div>
                    </div>
                    <div class="image-carousel">
                        <div class="testi-img">
                            <img src="assets/img/testimonial/1.png" alt="">
                            <h4>Dewey Tetzlaff</h4>
                        </div>
                        <div class="testi-img">
                            <img src="assets/img/testimonial/2.png" alt="">
                            <h4>Rebecka Filson</h4>
                        </div>
                        <div class="testi-img">
                            <img src="assets/img/testimonial/3.png" alt="">
                            <h4>Alva Ono</h4>
                        </div>
                        <div class="testi-img">
                            <img src="assets/img/testimonial/1.png" alt="">
                            <h4>Dewey Tetzlaff</h4>
                        </div>
                        <div class="testi-img">
                            <img src="assets/img/testimonial/2.png" alt="">
                            <h4>Rebecka Filson</h4>
                        </div>
                        <div class="testi-img">
                            <img src="assets/img/testimonial/3.png" alt="">
                            <h4>Alva Ono</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
</div>
