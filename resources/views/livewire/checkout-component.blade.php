<div>
    <div class="breadcrumb-area bg-12 text-center">
        <div class="container">
            <h1>Checkout</h1>
            <nav aria-label="breadcrumb">
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="index.html">Home</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Checkout</li>
                </ul>
            </nav>
        </div>
    </div>
    <!-- Breadcrumb Area End -->
    <!-- coupon-area start -->
    {{-- <div class="coupon-area pt-110">
        <div class="container">
            <div class="coupon-accordion">
                <h3>Returning customer? <span id="showlogin">Click here to login</span></h3>
                <div id="checkout-login" class="coupon-content">
                    <div class="coupon-info">
                        <p class="coupon-text">If you have shopped with us before, please enter your details in the boxes below. If you are a new customer please proceed to the Billing & Shipping section.</p>
                        <form action="#">
                            <p class="form-row-first">
                                <label>Username or email <span class="required">*</span></label>
                                <input type="text" />
                            </p>
                            <p class="form-row-last">
                                <label>Password  <span class="required">*</span></label>
                                <input type="text" />
                            </p>
                            <p class="form-row">
                                <input type="submit" value="Login" />
                                <label>
                                    <input type="checkbox" />
                                    Remember me
                                </label>
                            </p>
                            <p class="lost-password">
                                <a href="#">Lost your password?</a>
                            </p>
                        </form>
                    </div>
                </div>
                <h3>Have a coupon? <span id="showcoupon">Click here to enter your code</span></h3>
                <div id="checkout_coupon" class="coupon-checkout-content">
                    <div class="coupon-info">
                        <form action="#">
                            <p class="checkout-coupon">
                                <input type="text" placeholder="Coupon code" />
                                <input class="default-btn" type="submit" value="Apply Coupon" />
                            </p>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div> --}}
    <!-- coupon-area end -->
    <!-- checkout-area start -->
    <div class="checkout-area pb-90">
        <div class="container">
            <form action="#">
                <div class="row">
                    <div class="col-lg-6 col-md-12 col-sm-12">
                        <div class="checkbox-form">
                            <h3>Billing Details</h3>
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="checkout-form-list">
                                        <label>Name <span class="required">*</span></label>
                                        <input type="text" placeholder="" disabled value="{{$buyers->user->name}}" />
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="checkout-form-list">
                                        <label>Email <span class="required">*</span></label>
                                        <input type="text" placeholder="" disabled value="{{$buyers->user->email}}" />
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="checkout-form-list">
                                        <label>Address <span class="required">*</span></label>
                                        <input type="text" placeholder="" disabled value="{{$buyers->address}}" />
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="checkout-form-list">
                                        <label>Subdistrict <span class="required">*</span></label>
                                        <input type="text" placeholder="" disabled value="{{$buyers->subdistrict->subdistrict_name}}" />
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="checkout-form-list">
                                        <label>Town / City <span class="required">*</span></label>
                                        <input type="text" placeholder="" disabled value="{{$buyers->city->name}}" />
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="checkout-form-list">
                                        <label>State / County <span class="required">*</span></label>
                                        <input type="text" placeholder="" disabled value="{{$buyers->city->province->name}}"/>
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="checkout-form-list">
                                        <label>Phone  <span class="required">*</span></label>
                                        <input type="text" placeholder="" disabled value="{{'+62 - '.$buyers->phone_number}}" />
                                    </div>
                                </div>
                                @if($ongkir)
                                    <div class="alert alert-success">Ongkir Berhasil ditambahkan</div>
                                @endif
                                <h3>Pilih Kurir</h3>
                                <div class="checkout-form-list col-lg-12 row">
                                    @foreach ($kurir as $key => $item)
                                    <div class="form-check col-lg-4">
                                        <input class="form-check-input" type="radio" name="flexRadioDefault" id="inlineRadio{{$key+1}}" value="{{$kurir[$key]}}" wire:model="courier" wire:click="getOngkir()">
                                        <label class="form-check-label" for="inlineRadio{{$key+1}}">{{strtoupper($kurir[$key])}}</label>
                                    </div>
                                    @endforeach
                                    <div class="form-check col-lg-3">
                                        <input class="form-check-input" type="radio" name="flexRadioDefault" id="inlineRadio{{count($kurir)+1}}" value="1" wire:model="courier" wire:click="getOngkir()">
                                        <label class="form-check-label" for="inlineRadio{{count($kurir)+1}}">Lainnya</label>
                                    </div>
                                </div>
                                @if($courier === '1')
                                <div class="form-control">
                                        <label class="form-label">Isi Nama Kurir</label>
                                        <input type="text" wire:model="othercourier" pattern="[A-Za-z0-9]{2,20}">
                                </div>
                                @elseif ($harga)
                                        <section class="products col-lg-12 col-md-12 col-sm-12 row">
                                            <div class="row mt-4">
                                                @forelse ($harga[0]['costs'] as $r)
                                                <div class="col-lg-12 mt-4">
                                                    <div class="card">
                                                        <div class="card-body text-center">
                                                            <div class="col-md-12">
                                                                <h5>Total berat : {{$berat}} gr</h5>
                                                                <h5>{{Str::upper($courier) . ' ' . $r['service']}}</h5>
                                                                <h5>{{$r['description']}}</h5>
                                                                <h5>Rp. {{number_format($r['cost'][0]['value'])}}</h5>
                                                                <button type="button" class="btn btn-success" wire:click="ongkir({{$r['cost'][0]['value']}})"> Pilih Ongkir </button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                @endforeach
                                            </div>
                                        </section>
                                @else
                                <div class="alert alert-danger">
                                    Pilih Kurir
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-12 col-sm-12">
                            <div class="your-order">
                                <h3>Your order</h3>
                                <div class="your-order-table table-responsive">
                                    <table>
                                        <thead>
                                            <tr>
                                                <th class="product-name">Product</th>
                                                <th class="product-total">Total</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php
                                                $a = 0;
                                            @endphp
                                            @if (Cart::count() >0)
                                                @foreach (Cart::content() as $cartItem )
                                                    <tr class="cart_cartItem">
                                                        <td class="product-name">
                                                            {{$cartItem->name}} <strong class="product-quantity"> x {{$cartItem->qty}}</strong>
                                                        </td>
                                                        <td class="product-total">
                                                            <span class="amount">Rp.{{ number_format($cartItem->subtotal) }}</span>
                                                        </td>
                                                    </tr>
                                                    @php
                                                        dd($cartItem->model->stock->user->member->subdistrict_id);
                                                        // $a = $a + ($cartItem->options->stock->product->weight*$cartItem->qty);
                                                        // $this->berat = $a;
                                                        // $this->sellerlocation = ($cartItem->options->stock->user->member->subdistrict_id);
                                                        // $this->sellerid = ($cartItem->options->stock->user->id);
                                                    @endphp
                                                @endforeach
                                            @else
                                                    <tr>
                                                       <td colspan="6">
                                                           <a href="/shop" class="btn btn-primary">Go Shopping</a>
                                                       </td>
                                                    </tr>
                                            @endif
                                        </tbody>
                                        <tfoot>
                                            <tr class="cart-subtotal">
                                                <th>Cart Subtotal</th>
                                                <td><span class="amount">Rp.{{number_format(Cart::subtotal(2,'.',''))}}</span></td>
                                            </tr>
                                            @if ($discount && $discountOn)
                                            @php
                                                $this->discountNominal = (Cart::subtotal(2,'.','')+$ongkir)*$this->discount->discount/100;
                                                $this->subtotal = Cart::subtotal(2,'.','')-$this->discountNominal;
                                            @endphp
                                            <tr class="cart-subtotal">
                                                <th>Discount</th>
                                                <td><span class="amount">Rp.{{number_format($this->discountNominal)}}</span></td>
                                            </tr>
                                            @endif
                                            <tr class="shipping">
                                                <th>Shipping</th>
                                                <td>
                                                    <ul>
                                                        <li>
                                                            <label>
                                                                <span class="c-total-price"><span>Flat Rate:</span>@if($ongkir)Rp. {{number_format($ongkir)}}@else ~ @endif</span>
                                                            </label>
                                                        </li>
                                                    </ul>
                                                </td>
                                            </tr>
                                            <tr class="order-total">
                                                <th>Order Total</th>
                                                <td class="pro-price">
                                                    <strong><span class="amount">@if($discount && $discountOn) <span class="old-price small text-secondary"><del>Rp.{{number_format(Cart::subtotal(2,'.','')+$ongkir)}}</del></span> Rp.{{number_format($this->subtotal+$ongkir)}} @else Rp.{{number_format(Cart::subtotal(2,'.','')+$ongkir)}} @endif</span></strong>
                                                </td>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                                <div class="payment-method">
                                    <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
                                        <div class="panel">
                                            <div class="panel-heading" id="headingOne">
                                                <a href="#checkout" data-toggle="collapse" data-parent="#accordion">Cheque Payment</a>
                                            </div>
                                            <div id="checkout" class="collapse show">
                                                <div class="panel-body row">
                                                    <div class="col-lg-6">
                                                        <div class="checkout-form-list">
                                                            <label for="checkbox">Click below !!!</label><br>
                                                            <label class="switch">
                                                                <input type="checkbox" wire:model='discountOn' wire:click="discountTrigger">
                                                                <span class="slider round"></span>
                                                            </label>
                                                        </div>
                                                    </div>
                                                    @if ($discountOn)
                                                    <div class="col-lg-6">
                                                        <div class="checkout-form-list">
                                                            <label>Pilih Diskon  <span class="required">*</span></label>
                                                            <select name="discount" id="discount" class="form-control" wire:model="discountId">
                                                                <option value="0" disabled>*harap pilih diskon dibawah ini*</option>
                                                                @foreach ($discounts as $dsc )
                                                                    <option value="{{$dsc->id}}">{{$dsc->name}}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="order-button-payment">
                                        @if ($ongkir)
                                            <a class="default-btn" type="submit" value="Place order" wire:click='save'>Place order</a>
                                            @else
                                            <button class="btn btn-secondary">Ongkir belum ada</button>
                                        @endif
                                    </div>
                                </div>
                            </div>
                    </div>
                    <!-- <input type="text" placeholder="Coupon code">
                    <button type="submit">Apply coupon</button> -->

                </div>
                </form>
            </div>
        </div>
        <!-- checkout-area end -->
 </div>
