<div>
        <!-- Breadcrumb Area Start -->
        <div class="breadcrumb-area bg-12 text-center">
            <div class="container">
                <h1>Shop</h1>
                <nav aria-label="breadcrumb">
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="index.html">Home</a></li>
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
                                            <option value="price-asc">Sort Price</option>
                                            <option value="price-desc">Sort Price Highest</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="ht-product-shop tab-content text-center">
                            <div class="tab-pane active show fade" id="grid" role="tabpanel">
                                <div class="custom-row">
                                    @foreach ($stocks as $stock )
                                    @php
                                        if ($stock->discount) {
                                            $priceold = $stock->product->price;
                                            $price = $stock->product->price-($stock->product->price*$stock->discount->discount/100);
                                        }else{
                                            $price = $stock->product->price;
                                        }
                                        $image = App\Models\ProductPicture::where('product_id', '=', $stock->product->id)->first();

                                        $img = $image->image ?? '1.jpg';
                                    @endphp
                                        <div class="custom-col">

                                            <div class="single-product-item">
                                                <div class="product-image">
                                                    <a>
                                                        <img src="{{ asset('upload/product/' . $img) }}" alt="">
                                                    </a>
                                                    <div class="product-hover">
                                                        <ul class="hover-icon-list">
                                                            <li>
                                                                <a href="#"><i class="icon icon-Restart"></i></a>
                                                            </li>
                                                            <li><a href="{{ asset('upload/product/' . $img) }}" data-toggle="modal" data-target=".productModal{{$stock->id}}" onclick="setIndex({{$stock->id}})"><i class="icon icon-Search"></i></a></li>
                                                        </ul>
                                                        <a type="button" href="#" class="p-cart-btn default-btn" wire:click="store({{$stock->id}}, '{{$stock->product->name}}' , {{$price}})">Add to cart</a>
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
                                                    <h6><a href="product-details.html">Stok tersisa : {{$stock->stock}}</a></h6>
                                                    <h5><a href="product-details.html">{{$stock->product->name}}</a></h5>
                                                    <div class="pro-price">

                                                        @if ($stock->discount)
                                                            <span class="old-price">Rp.{{ number_format($priceold) }}</span>
                                                            <span class="new-price">Rp.{{ number_format($price) }}</span>
                                                        @else
                                                            <span class="new-price">Rp.{{ number_format($price) }}</span>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                            <div class="tab-pane fade text-left" id="list" role="tabpanel">
                                @foreach ($stocks as $stock )
                                @php
                                    if ($stock->discount) {
                                        $priceold = $stock->product->price;
                                        $price = $stock->product->price-($stock->product->price*$stock->discount->discount/100);
                                    }else{
                                        $price = $stock->product->price;
                                    }
                                    $image = App\Models\ProductPicture::where('product_id', '=', $stock->product->id)->first();

                                    $img = $image->image ?? 'notFound';
                                @endphp
                                    <div class="single-product-item">
                                        <div class="product-image">
                                            <a>
                                                <img src="{{ asset('upload/product/' . $img) }}" alt="">
                                            </a>
                                            <div class="product-hover">
                                                <ul class="hover-icon-list">
                                                    <li>
                                                        <a href="#"><i class="icon icon-Restart"></i></a>
                                                    </li>
                                                    <li><a href="{{ asset('upload/product/' . $img) }}" data-toggle="modal" data-target=".productModal{{$stock->id}}" onclick="setIndex({{$stock->id}})"><i class="icon icon-Search"></i></a></li>
                                                </ul>
                                            </div>
                                        </div>
                                        <div class="product-text">
                                            <h5><a href="product-details.html">{{$stock->product->name}}</a></h5>
                                            <div class="product-rating">
                                                <i class="fa fa-star-o color"></i>
                                                <i class="fa fa-star-o color"></i>
                                                <i class="fa fa-star-o color"></i>
                                                <i class="fa fa-star-o"></i>
                                                <i class="fa fa-star-o"></i>
                                            </div>
                                            <div class="pro-price">
                                                @if ($stock->discount)
                                                    <span class="new-price">Rp.{{ number_format($price) }}<</span>
                                                    <span class="old-price">Rp.{{ number_format($priceold) }}</span>
                                                @else
                                                    <span class="new-price">Rp.{{ number_format($price) }}<</span>
                                                @endif
                                            </div>
                                            <p>{{$stock->description}}</p>
                                            <a type="button" href="#" class="p-cart-btn default-btn" wire:click="store({{$stock->id}}, '{{$stock->product->name}}' , {{$price}})">Add to cart</a>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        <div class="d-flex justify-content-center">
                            {!! $stocks->links() !!}
                        </div>
                    </div>
                    <div class="col-xl-3 col-lg-4">
                        <div class="sidebar-wrapper">
                            <h3>Layered Navigation</h3>
                            <div class="sidebar-widget">
                                <h3>Categories</h3>
                                <div class="sidebar-widget-option-wrapper">
                                    @foreach ($categories as $category )
                                        <div class="sidebar-widget-option">
                                            <a href="#" wire:click="category({{$category->id}})">{{$category->name}}</a>
                                        </div>
                                    @endforeach
                                    <div class="sidebar-widget-option">
                                        <a href="#" wire:click="category({{0}})">Reset</a>
                                    </div>
                                </div>
                            </div>
                            <div class="sidebar-widget price-widget">
                                <h3>Price Filter</h3>
                                <label for="">Range</label>
                                <div class="row">
                                    <div class="col-md-6">
                                        <label for="">From</label>
                                        <input type="number" wire:model="min_price" class="form-control" >
                                    </div>
                                    <div class="col-md-6">
                                        <label for="">To</label>
                                        <input type="number" wire:model="max_price" class="form-control" >
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
        <!-- QUICKVIEW PRODUCT -->
        @foreach ($stocks as $stock )
        @php
            if ($stock->discount) {
                $priceold = $stock->product->price;
                $price = $stock->product->price-($stock->product->price*$stock->discount->discount/100);
            }else{
                $price = $stock->product->price;
            }
        @endphp
            <div class="modal fade productModal{{$stock->id}}" id="productModal" tabindex="-1" role="dialog">
                <div class="modal-dialog" role="document" style="overflow: unset !important;">
                    <div class="modal-content">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true"><i class="fa fa-times"></i></span></button>
                        <div class="quick-view-container">
                            <div class="column-left">
                                <div class="tab-content product-details-large" id="myTabContent">
                                    @if ($pictureId)
                                    @foreach ($pictures as $key => $picture )
                                    <div class="tab-pane fade @if ($key == 0) show active @endif" id="single-slide{{$key+1}}" role="tabpanel" aria-labelledby="single-slide-tab-{{$key+1}}">
                                        <div class="single-product-img">
                                            <img src="{{ asset('upload/product/'. $picture->image) }}" alt="">
                                        </div>
                                    </div>
                                    @endforeach
                                    @endif
                                </div>
                                <div class="single-product-menu">
                                    <div class="nav single-slide-menu" role="tablist">
                                        @if ($pictureId)
                                        @foreach ($pictures as $key => $picture )
                                            <div class="single-tab-menu">
                                                <a @if ($key == 0) class="active" @endif data-toggle="tab" id="single-slide-tab-{{$key+1}}" href="#single-slide{{$key+1}}"><img src="{{ asset('upload/product/'. $picture->image) }}" alt="" ></a>
                                            </div>
                                        @endforeach
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="column-right">
                                <div class="quick-view-text">
                                    <h2>{{$stock->product->name}}</h2>
                                    @if ($stock->discount)
                                        <h3 class="q-product-price">Rp.{{ number_format($price) }}<span class="old-price">Rp.{{ number_format($priceold) }}</span></h3>
                                    @else
                                        <h3 class="q-product-price">Rp.{{ number_format($price) }}</span></h3>
                                    @endif
                                    <p>{{$stock->product->description}}</p>
                                    <div class="input-cart">
                                        <a type="button" href="#" class="p-cart-btn default-btn" wire:click="store({{$stock->id}}, '{{$stock->product->name}}' , {{$price}})">Add to cart</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
        <!-- END QUICKVIEW PRODUCT -->
        <input type="hidden" name="pictureId" id="pictureId" wire:model='pictureId'>
        <script>
            function setIndex(id) {
                index = id;
                $("#pictureId").val(index);
            }
        </script>
</div>

