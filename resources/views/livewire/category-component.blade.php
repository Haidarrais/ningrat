<div>
    <!-- Breadcrumb Area Start -->
    <div class="breadcrumb-area bg-12 text-center">
        <div class="container">
            <h1>Shop</h1>
            <nav aria-label="breadcrumb">
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="/">Home</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Shop</li>
                </ul>
            </nav>
        </div>
    </div>
    <!-- Breadcrumb Area End -->
    <!-- Shop Area Start -->
    <div class="shop-area pt-110 pb-100 bg-gray mb-95">
        <div class="container">
            <div class="row">
                <div class="order-xl-2 order-lg-2 col-xl-9 col-lg-8">
                    <div class="ht-product-tab">
                        <div class="ht-tab-content">
                            <div class="nav" role="tablist">
                                <a class="active grid" href="#grid" data-toggle="tab" role="tab" aria-selected="true" aria-controls="grid"><i class="fa fa-th"></i></a>
                                <a class="list" href="#list" data-toggle="tab" role="tab" aria-selected="false" aria-controls="list"><i class="fa fa-list"></i></a>
                            </div>
                            <div class="shop-items">
                                <span>Showing 1 to 9 of 11 (2 Pages) </span>
                            </div>
                        </div>
                        <div class="shop-results-wrapper">
                            <div class="shop-results"><span>Show:</span>
                                <div class="shop-select">
                                    <select name="number" id="number" class="use-choosen" wire:model="pageSize" wire:change="gotoPage(1)">
                                        <option value="6">6</option>
                                        <option value="12">12</option>
                                        <option value="24">24</option>
                                        <option value="48">48</option>
                                        <option value="96">96</option>
                                    </select>
                                </div>
                            </div>
                            <div class="shop-results"><span>Sort By:</span>
                                <div class="shop-select">
                                    <select name="orderBy" id="sort" class="use-choosen" wire:model="sorting">
                                        <option value="default">Default sorting</option>
                                        <option value="date">Date sorting</option>
                                        <option value="price">Sort Price</option>
                                        <option value="price-desc">Sort Price Highest</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="ht-product-shop tab-content text-center">
                        <div class="tab-pane active show fade" id="grid" role="tabpanel">
                            <div class="custom-row">
                                @foreach ($product as $prod )
                                    <div class="custom-col">
                                        <div class="single-product-item">
                                            <div class="product-image">
                                                <a href="product-details.html">
                                                    <img src="{{ asset('assets/img/product/'.$prod->image.'.png') }}" alt="">
                                                </a>
                                                <div class="product-hover">
                                                    <ul class="hover-icon-list">
                                                        <li>
                                                            <a href="wishlist.html"><i class="icon icon-Heart"></i></a>
                                                        </li>
                                                        <li>
                                                            <a href="#"><i class="icon icon-Restart"></i></a>
                                                        </li>
                                                        <li><a href="{{ asset('assets/img/product/'.$prod->image.'.png') }}" data-toggle="modal" data-target="#productModal"><i class="icon icon-Search"></i></a></li>
                                                    </ul>
                                                    <a type="button" href="#" class="p-cart-btn default-btn" wire:click="store({{$prod->id}}, '{{$prod->name}}' , {{$prod->price}})">Add to cart</a>
                                                </div>
                                            </div>
                                            <div class="product-text">
                                                {{-- <div class="product-rating">
                                                    <i class="fa fa-star-o color"></i>
                                                    <i class="fa fa-star-o color"></i>
                                                    <i class="fa fa-star-o color"></i>
                                                    <i class="fa fa-star-o"></i>
                                                    <i class="fa fa-star-o"></i>
                                                </div> --}}
                                                <h5><a href="product-details.html">{{$prod->name}}</a></h5>
                                                <div class="pro-price">
                                                    <span class="new-price">Rp.{{ number_format($prod->price) }}</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        <div class="tab-pane fade text-left" id="list" role="tabpanel">
                            @foreach ($product as $prod )
                                <div class="single-product-item">
                                    <div class="product-image">
                                        <a href="product-details.html">
                                            <img src="assets/img/product/1.jpg" alt="">
                                        </a>
                                        <div class="product-hover">
                                            <ul class="hover-icon-list">
                                                <li>
                                                    <a href="wishlist.html"><i class="icon icon-Heart"></i></a>
                                                </li>
                                                <li>
                                                    <a href="#"><i class="icon icon-Restart"></i></a>
                                                </li>
                                                <li><a href="assets/img/product/1.jpg" data-toggle="modal" data-target="#productModal"><i class="icon icon-Search"></i></a></li>
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="product-text">
                                        <h5><a href="product-details.html">{{$prod->name}}</a></h5>
                                        <div class="product-rating">
                                            <i class="fa fa-star-o color"></i>
                                            <i class="fa fa-star-o color"></i>
                                            <i class="fa fa-star-o color"></i>
                                            <i class="fa fa-star-o"></i>
                                            <i class="fa fa-star-o"></i>
                                        </div>
                                        <div class="pro-price">
                                            <span class="new-price">{{$prod->price}}</span>
                                            <span class="old-price">$122.00</span>
                                        </div>
                                        <p>{{$prod->description}}</p>

                                        <a type="button" href="#" class="p-cart-btn default-btn" wire:click.prevent="store('{{$prod->id}}, '{{$prod->name}}' , {{$prod->price}})">Add to cart</a>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    <div class="d-flex justify-content-center">
                        {!! $product->links() !!}
                    </div>
                </div>
                <div class="col-xl-3 col-lg-4">
                    <div class="sidebar-wrapper">
                        <h3>Layered Navigation</h3>
                        <div class="sidebar-widget">
                            <h3>Categories</h3>
                            <div class="sidebar-widget-option-wrapper">
                                @foreach ($categories as $category )
                                    <div class="sidebar-widget-option" >
                                        <a href="{{ route('product.category', $category->id) }}" >{{$category->name}}</a>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        <div class="sidebar-widget price-widget">
                            <h3>Price Filter</h3>
                            <div class="price-slider-container">
                                <div id="slider-range"></div>
                                <div class="price_slider_amount">
                                    <div class="slider-values">
                                        <input type="text" id="amount" name="price"  placeholder="Add Your Price" />
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="sidebar-widget">
                            <h3>Color</h3>
                            <div class="sidebar-widget-option-wrapper">
                                <div class="sidebar-widget-option">
                                    <input type="checkbox" id="black">
                                    <label for="black">Black <span>(4)</span></label>
                                </div>
                                <div class="sidebar-widget-option">
                                    <input type="checkbox" id="blue">
                                    <label for="blue">Blue <span>(3)</span></label>
                                </div>
                                <div class="sidebar-widget-option">
                                    <input type="checkbox" id="brown">
                                    <label for="brown">Brown <span>(3)</span></label>
                                </div>
                                <div class="sidebar-widget-option">
                                    <input type="checkbox" id="white">
                                    <label for="white">White <span>(3)</span></label>
                                </div>
                            </div>
                        </div>
                        <div class="sidebar-widget">
                            <h3>Manufacturer</h3>
                            <div class="sidebar-widget-option-wrapper">
                                <div class="sidebar-widget-option">
                                    <input type="checkbox" id="dior">
                                    <label for="dior">Christian Dior <span>(6)</span></label>
                                </div>
                                <div class="sidebar-widget-option">
                                    <input type="checkbox" id="ferragamo">
                                    <label for="ferragamo">ferragamo <span>(7)</span></label>
                                </div>
                                <div class="sidebar-widget-option">
                                    <input type="checkbox" id="hermes">
                                    <label for="hermes">hermes <span>(8)</span></label>
                                </div>
                                <div class="sidebar-widget-option">
                                    <input type="checkbox" id="louis">
                                    <label for="louis">louis vuitton <span>(6)</span></label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="sidebar-banner-img">
                        <a href="#"><img src="assets/img/banner/6.png" alt=""></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Shop Area End -->
</div>

