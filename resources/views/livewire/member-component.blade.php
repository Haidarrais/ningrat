<div>
    <@if (Auth::user()->member)
    <!-- Breadcrumb Area Start -->
    <div class="breadcrumb-area bg-12 text-center">
        <div class="container">
            <h1>Member</h1>
            <nav aria-label="breadcrumb">
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="index.html">Home</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Member</li>
                </ul>
            </nav>
        </div>
    </div>
    <!-- Breadcrumb Area End -->
    <!-- Shop Area Start -->
    <div class="shop-area pt-110 pb-100 bg-gray mb-95 shop-full-width">
                <div class="container">
                    <div class="ht-product-tab">
                        <div class="ht-tab-content">
                            <div class="nav" role="tablist">
                                <a class="active grid" href="#grid" data-toggle="tab" role="tab" aria-selected="true" aria-controls="grid"><i class="fa fa-th"></i></a>
                            </div>
                        </div>
                        <div class="shop-results-wrapper">
                            <div class="shop-results"><span>Provinsi : </span>
                                <div class="shop-select">
                                    <select name="number" id="number" wire:model="province">
                                        @foreach ($locations as $location )
                                            <option value="{{$location->id}}">{{$location->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="shop-results"><span>Kota : </span>
                                <div class="shop-select">
                                    <select name="sort" id="sort" wire:model="city">
                                        <option value="selected" selected>--Pilih Kota--</option>
                                        @foreach ($cities as $city)
                                            <option value="{{$city->city_id}}">{{$city->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="ht-product-shop tab-content text-center">
                        <div class="tab-pane active show fade" id="grid" role="tabpanel">
                            <div class="custom-row" id="member_row">
                                @forelse ($members as $member )
                                    <div class="custom-col">
                                            <div class="single-product-item">
                                                <div class="product-image">
                                                    <a>
                                                        <img src="{{ asset('assets/img/product/1.jpg') }}" alt="">
                                                    </a>
                                                    <div class="product-hover">
                                                        @if (Auth::user()->isCustomer())
                                                        <a class="p-cart-btn" href="{{ route('member.shopc', ['id'=>$member->user_id, 'name' => $member->user->name]) }}" >Belanja di {{$member->user->name}}</a>
                                                        @else
                                                        <a class="p-cart-btn" href="{{ route('member.shopr', ['id'=>$member->user_id, 'name' => $member->user->name]) }}" >Belanja di {{$member->user->name}}</a>
                                                        @endif
                                                    </div>
                                                </div>
                                                <div class="product-text">
                                                    <div class="product-rating">
                                                        @php
                                                            $star = 5;
                                                        @endphp
                                                        @for ($i = 0; $i < $member->avgRating; $i++)
                                                            <i class="fa fa-star-o color"></i>
                                                        @endfor
                                                        @for ($i = 0; $i < $star-$member->avgRating; $i++)
                                                            <i class="fa fa-star-o"></i>
                                                        @endfor
                                                        {{-- <i class="fa fa-star-o color"></i>
                                                        <i class="fa fa-star-o color"></i>
                                                        <i class="fa fa-star-o"></i> --}}
                                                        ({{number_format($member->avgRating, 1)}})
                                                    </div>
                                                    <h5><a>{{$member->user->name}}</a></h5>
                                                    <div class="pro-price">
                                                        <span class="new-price">{{$member->city->name}}</span>
                                                    </div>
                                                </div>
                                            </div>
                                    </div>
                                @empty
                                    <script>
                                        Livewire.emit('nothing')
                                    </script>
                                @endforelse
                            </div>
                        </div>
                    </div>
                    <div class="pagination-wrapper">
                    </div>
                </div>
    </div>
    <!-- Shop Area End -->
    @else
    @php
        $role = Auth::user()->getRoleNames();
        return redirect()->to('/' . $role[0] . '/profile');
    @endphp
    @endif
</div>
