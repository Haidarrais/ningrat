<div>
    <!-- Breadcrumb Area Start -->
    <div class="breadcrumb-area bg-12 text-center">
        <div class="container">
            <h1>All Products</h1>
            <nav aria-label="breadcrumb">
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="/">Home</a></li>
                    <li class="breadcrumb-item active" aria-current="page">All Products</li>
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
                                // if (!$stock->discount) {
                                // $priceold = $stock->product->price;
                                // $price = $stock->product->price-($stock->product->price*!$stock->discount->discount/100);
                                // }else{
                                $price = $stock->product->price;
                                // }
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
                                                    <li><a onclick="setIndex({{$stock->product_id}})"><i class="icon icon-Search"></i></a></li>
                                                </ul>
                                                {{-- <a type="button" href="#" class="p-cart-btn default-btn" wire:click="store({{$stock->id}}, '{{$stock->product->name}}' , {{$price}})">Add to cart</a> --}}
                                            </div>
                                        </div>
                                        <div class="product-text p-2">
                                            {{-- <div class="product-rating">
                                                    <i class="fa fa-star-o color"></i>
                                                    <i class="fa fa-star-o color"></i>
                                                    <i class="fa fa-star-o color"></i>
                                                    <i class="fa fa-star-o"></i>
                                                    <i class="fa fa-star-o"></i>
                                                </div> --}}
                                            <h6><a>Stok tersisa : @if (Auth::check())
                                                {{$stock->stock}}
                                            @else
                                                <span class="small">Login terlebih dahulu</span>
                                            @endif</a></h6>
                                            <blockquote class="blockquote">
                                                <h5 class="mb-0"><a>{{$stock->product->name}}</a></h5>
                                                <footer class="blockquote-footer small text-primary">Varian : {{$stock->product->variant->subVariant->name ?? "unset"}} <span class="text-secondary">{{$stock->product->variant->name ?? "unset"}}</span></footer>
                                            </blockquote>
                                            <div class="pro-price">

                                                {{-- @if (!$stock->discount)
                                                <span class="old-price">Rp.{{ number_format($priceold) }}</span>
                                                <span class="new-price">Rp.{{ number_format($price) }}</span>
                                                @else --}}
                                                <span class="new-price">Rp.{{ number_format($price) }}</span>
                                                {{-- @endif --}}
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
                            // if (!$stock->discount) {
                            // $priceold = $stock->product->price;
                            // $price = $stock->product->price-($stock->product->price*!$stock->discount->discount/100);
                            // }else{
                            $price = $stock->product->price;
                            // }
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
                                            <li><a onclick="setIndex({{$stock->product_id}})"><i class="icon icon-Search"></i></a></li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="product-text p-2">
                                    <h6><a>Stok tersisa : @if (Auth::check())
                                        {{$stock->stock}}
                                    @else
                                        <span class="small">Login terlebih dahulu</span>
                                    @endif</a></h6>
                                    <h5><a>{{$stock->product->name}}</a></h5>
                                    {{-- <div class="product-rating">
                                            <i class="fa fa-star-o color"></i>
                                            <i class="fa fa-star-o color"></i>
                                            <i class="fa fa-star-o color"></i>
                                            <i class="fa fa-star-o"></i>
                                            <i class="fa fa-star-o"></i>
                                        </div> --}}
                                    <div class="pro-price">
                                        {{-- @if (!$stock->discount)
                                        <span class="new-price">Rp.{{ number_format($price) }}
                                            << /span>
                                                <span class="old-price">Rp.{{ number_format($priceold) }}</span>
                                                @else --}}
                                                <span class="new-price">Rp.{{ number_format($price) }}
                                                    </span>
                                                        {{-- @endif --}}
                                    </div>
                                    <p>{{$stock->description}}</p>
                                    {{-- <a type="button" href="#" class="p-cart-btn default-btn" wire:click="store({{$stock->id}}, '{{$stock->product->name}}' , {{$price}})">Add to cart</a> --}}
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
                            <h3 wire:click="$toggle('showDivCat')">Categories @if ($showDivCat)<small class="text-secondary">hide</small>@else <small class="text-secondary">show</small> @endif</h3>
                            @if ($showDivCat)
                            <div class="sidebar-widget-option-wrapper" id="categories">
                                @foreach ($categories as $category )
                                @if ($category->id == $category->parent_id)
                                <div class="sidebar-widget-option">
                                    <a href="#" wire:click="category({{$category->id}})" @if($this->category == $category->id) class="text-success" @endif>{{$category->name}}</a>
                                </div>
                                @endif
                                @foreach ($categories->where('parent_id', '=', $category->id) as $sub)
                                @if ($sub->id != $sub->parent_id)
                                <div class="sidebar-widget-option">
                                    <a class="ml-3 @if($this->category == $sub->id) text-success @else text-secondary @endif" href="#" wire:click="category({{$sub->id}})">- {{$sub->name}}</a>
                                </div>
                                @endif
                                @endforeach
                                @endforeach
                                <div class="sidebar-widget-option">
                                    <a href="#" wire:click="category({{0}})">Reset</a>
                                </div>
                            </div>
                            @endif
                            <h3 wire:click="$toggle('showDivVar')">Variant @if ($showDivVar)<small class="text-secondary">hide</small>@else <small class="text-secondary">show</small> @endif</h3>
                            @if ($showDivVar)
                            <div class="sidebar-widget-option-wrapper" id="categories">
                                @foreach ($variants as $variant )
                                @if ($variant->id == $variant->parent_id)
                                <div class="sidebar-widget-option">
                                    <a href="#" wire:click="variant({{$variant->id}})" @if($this->variant == $variant->id) class="text-success" @endif>{{$variant->name}}</a>
                                </div>
                                @endif
                                @foreach ($variants->where('parent_id', '=', $variant->id) as $sub)
                                @if ($sub->id != $sub->parent_id)
                                <div class="sidebar-widget-option">
                                    <a class="ml-3 @if($this->variant == $sub->id) text-success @else text-secondary @endif" href="#" wire:click="variant({{$sub->id}})">- {{$sub->name}}</a>
                                </div>
                                @endif
                                @endforeach
                                @endforeach
                                <div class="sidebar-widget-option">
                                    {{$stocks}}
                                    <a href="#" wire:click="variant({{0}})">Reset</a>
                                </div>
                            </div>
                            @endif
                        </div>
                        <div class="sidebar-widget price-widget">
                            <h3>Price Filter</h3>
                            <label for="">Range</label>
                            <div class="row">
                                <div class="col-md-6">
                                    <label for="">From</label>
                                    <input type="number" wire:model="min_price" class="form-control">
                                </div>
                                <div class="col-md-6">
                                    <label for="">To</label>
                                    <input type="number" wire:model="max_price" class="form-control">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Shop Area End -->
        <div class="modal fade productModal" id="productModal" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document" style="overflow: unset !important;">
                <div class="modal-content">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true"><i class="fa fa-times"></i></span></button>
                    <div class="quick-view-container">
                        <div class="column-left">
                            <div class="tab-content product-details-large" id="myTabContent">
                                <div class="tab-pane fade show active" id="single-slide1" role="tabpanel" aria-labelledby="single-slide-tab-1">
                                    <div class="single-product-img">
                                        <img src="assets/img/product/1.jpg" alt="">
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="single-slide2" role="tabpanel" aria-labelledby="single-slide-tab-2">
                                    <div class="single-product-img">
                                        <img src="assets/img/product/2.jpg" alt="">
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="single-slide3" role="tabpanel" aria-labelledby="single-slide-tab-3">
                                    <div class="single-product-img">
                                        <img src="assets/img/product/3.jpg" alt="">
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="single-slide4" role="tabpanel" aria-labelledby="single-slide-tab-4">
                                    <div class="single-product-img">
                                        <img src="assets/img/product/4.jpg" alt="">
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="single-slide5" role="tabpanel" aria-labelledby="single-slide-tab-5">
                                    <div class="single-product-img">
                                        <img src="assets/img/product/5.jpg" alt="">
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="single-slide6" role="tabpanel" aria-labelledby="single-slide-tab-6">
                                    <div class="single-product-img">
                                        <img src="assets/img/product/6.jpg" alt="">
                                    </div>
                                </div>
                            </div>
                            <div class="single-product-menu">
                                <div class="nav single-slide-menu" role="tablist" id="myTabList">
                                    <div class="single-tab-menu">
                                        <a class="active" data-toggle="tab" id="single-slide-tab-1" href="#single-slide1"><img src="assets/img/product/1.jpg" alt=""></a>
                                    </div>
                                    <div class="single-tab-menu">
                                        <a data-toggle="tab" id="single-slide-tab-2" href="#single-slide2"><img src="assets/img/product/2.jpg" alt=""></a>
                                    </div>
                                    <div class="single-tab-menu">
                                        <a data-toggle="tab" id="single-slide-tab-3" href="#single-slide3"><img src="assets/img/product/3.jpg" alt=""></a>
                                    </div>
                                    <div class="single-tab-menu">
                                        <a data-toggle="tab" id="single-slide-tab-4" href="#single-slide4"><img src="assets/img/product/4.jpg" alt=""></a>
                                    </div>
                                    <div class="single-tab-menu">
                                        <a data-toggle="tab" id="single-slide-tab-5" href="#single-slide5"><img src="assets/img/product/5.jpg" alt=""></a>
                                    </div>
                                    <div class="single-tab-menu">
                                        <a data-toggle="tab" id="single-slide-tab-6" href="#single-slide6"><img src="assets/img/product/6.jpg" alt=""></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="column-right">
                            <div class="quick-view-text">
                                <h2 id="m-product-name"></h2>
                                <h3 class="q-product-price" id="m-product-price"></span></h3>
                                <p id="m-product-desc"></p>
                                <div class="input-cart" id="m-product-cart">
                                    {{-- <a type="button" href="#" class="p-cart-btn default-btn">Add to cart</a> --}}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- END QUICKVIEW PRODUCT -->
        <script>
            function setIndex(id) {
                // index = id;
                // console.log(index);
                var url = "{{route('picture.show', ": id ")}}";
                url = url.replace(":id", id);
                $.ajax({
                    type: 'GET',
                    url: url,
                    success: function(data) {
                        let picture = data.data
                        let htmlC = ``
                        let htmlL = ``
                        $.each(picture, (key, value) => {
                            if (key == 0) {
                                htmlC += `<div class="tab-pane fade show active" id="single-slide${key+1}" role="tabpanel" aria-labelledby="single-slide-tab-${key+1}">
                                        <div class="single-product-img">
                                            <img src="{{ asset('upload/product/${value.image}') }}" alt="tidak ada gambar">
                                        </div>
                                    </div>`
                                htmlL += `<div class="single-tab-menu">
                                        <a class="active" data-toggle="tab" id="single-slide-tab-${key+1}" href="#single-slide${key+1}"><img src="{{ asset('upload/product/${value.image}') }}" alt=""></a>
                                    </div>`
                            } else {
                                htmlC += `<div class="tab-pane fade" id="single-slide${key+1}" role="tabpanel" aria-labelledby="single-slide-tab-${key+1}">
                                        <div class="single-product-img">
                                            <img src="{{ asset('upload/product/${value.image}') }}" alt="tidak ada gambar">
                                        </div>
                                    </div>`
                                htmlL += `<div class="single-tab-menu">
                                        <a  data-toggle="tab" id="single-slide-tab-${key+1}" href="#single-slide${key+1}"><img src="{{ asset('upload/product/${value.image}') }}" alt=""></a>
                                    </div>`
                            }
                        });
                        $('#m-product-name').empty()
                        $('#m-product-price').empty()
                        $('#m-product-desc').empty()
                        $('#m-product-cart').empty()
                        $('#myTabList').empty()
                        $('#myTabContent').empty()
                        $('#m-product-cart').empty()
                        $('.single-slide-menu').slick('unslick')
                        $('#m-product-name').html(data.stock.product.name)
                        $('#m-product-price').html("Rp. " + addCommas(data.stock.product.price))
                        $('#m-product-desc').html(data.stock.product.description)
                        // $('#m-product-cart').html(`<a type="button" href="#" class="p-cart-btn default-btn" wire:click="store(${data.stock.id}, '${data.stock.product.name}' , ${data.stock.product.price})">Add to cart</a>`)
                        $('#myTabList').html(htmlL)
                        $('#myTabContent').html(htmlC)

                        $('#productModal').modal('show')

                        $('.single-slide-menu').slick({
                            dots: false,
                            arrows: false,
                            slidesToShow: 4,
                            responsive: [{
                                    breakpoint: 1200,
                                    settings: {
                                        slidesToShow: 3,
                                        slidesToScroll: 3
                                    }
                                },
                                {
                                    breakpoint: 991,
                                    settings: {
                                        slidesToShow: 3,
                                        slidesToScroll: 2
                                    }
                                },
                                {
                                    breakpoint: 480,
                                    settings: {
                                        slidesToShow: 3,
                                        slidesToScroll: 3
                                    }
                                }
                            ]
                        });
                        $('.modal').on('shown.bs.modal', function(e) {
                            $('.single-slide-menu').resize();
                        })
                        $('.single-slide-menu a').on('click', function(e) {
                            e.preventDefault();
                            var $href = $(this).attr('href');
                            $('.single-slide-menu a').removeClass('active');
                            $(this).addClass('active');
                            $('.product-details-large .tab-pane').removeClass('active show');
                            $('.product-details-large ' + $href).addClass('active show');
                        });
                    }
                });
            };
        </script>
    </div>

    @section('script')
    @foreach ($stocks as $item)
    <script>
        if (window.jQuery) {
            function addCommas(nStr) {
                nStr += '';
                x = nStr.split('.');
                x1 = x[0];
                x2 = x.length > 1 ? '.' + x[1] : '';
                var rgx = /(\d+)(\d{3})/;
                while (rgx.test(x1)) {
                    x1 = x1.replace(rgx, '$1' + ',' + '$2');
                }
                return x1 + x2;
            }

            function slickRun() {
                $('.single-slide-menu').slick('unslick')
                $('.single-slide-menu').slick({
                    dots: false,
                    arrows: false,
                    slidesToShow: 4,
                    responsive: [{
                            breakpoint: 1200,
                            settings: {
                                slidesToShow: 3,
                                slidesToScroll: 3
                            }
                        },
                        {
                            breakpoint: 991,
                            settings: {
                                slidesToShow: 3,
                                slidesToScroll: 2
                            }
                        },
                        {
                            breakpoint: 480,
                            settings: {
                                slidesToShow: 3,
                                slidesToScroll: 3
                            }
                        }
                    ]
                });
                $('.modal').on('shown.bs.modal', function(e) {
                    $('.single-slide-menu').resize();
                })
                $('.single-slide-menu a').on('click', function(e) {
                    e.preventDefault();
                    var $href = $(this).attr('href');
                    $('.single-slide-menu a').removeClass('active');
                    $(this).addClass('active');
                    $('.product-details-large .tab-pane').removeClass('active show');
                    $('.product-details-large ' + $href).addClass('active show');
                });
            }
        }
    </script>
    @endforeach
    @endsection
    {{--
@push('modal')
<!-- QUICKVIEW PRODUCT -->
@foreach ($stocks as $stock )
    @php
        if (!$stock->discount) {
            $priceold = $stock->product->price;
            $price = $stock->product->price-($stock->product->price*!$stock->discount->discount/100);
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
                    <div class="tab-content product-details-large" id="myTabContent{{$stock->product_id}}">

                    </div>
                    <div class="single-product-menu">
                        <div class="nav single-slide-menu" role="tablist" id="myTabList{{$stock->product_id}}">

                        </div>
                    </div>
                </div>
                <div class="column-right">
                    <div class="quick-view-text">
                        <h2>{{$stock->product->name}}</h2>
                        @if (!$stock->discount)
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
@endpush --}}