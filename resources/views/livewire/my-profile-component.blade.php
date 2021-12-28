<div>
    <div class="breadcrumb-area bg-12 text-center">
        <div class="container">
            <h1>My Account</h1>
            <nav aria-label="breadcrumb">
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="/">Home</a></li>
                    <li class="breadcrumb-item active" aria-current="page">My Account</li>
                </ul>
            </nav>
        </div>
    </div>
    <!-- Breadcrumb Area End -->
    <!-- my account wrapper start -->
    <div class="my-account-wrapper pt-120 pb-120">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <!-- My Account Page Start -->
                    <div class="myaccount-page-wrapper">
                        <!-- My Account Tab Menu Start -->
                        <div class="row">
                            <div class="col-lg-3 col-md-4">
                                <div class="myaccount-tab-menu nav" role="tablist">
                                    <a href="#account-info"  class="active"  data-toggle="tab"><i class="fa fa-user"></i> Account Details</a>
                                    <a href="#orders" data-toggle="tab"><i class="fa fa-cart-arrow-down"></i> Orders</a>
                                    {{-- <a href="#payment-method" data-toggle="tab"><i class="fa fa-credit-card"></i> Payment
                                        Method</a> --}}
                                        <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="text-danger">
                                            <i class="fa fa-sign-out"></i>
                                            Logout
                                        </a>
                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                        @csrf
                                    </form>
                                </div>
                            </div>
                            <!-- My Account Tab Menu End -->
                            <!-- My Account Tab Content Start -->
                            <div class="col-lg-9 col-md-8">
                                <div class="tab-content" id="myaccountContent">
                                    <!-- Single Tab Content Start -->
                                    <div class="tab-pane fade  show active" id="account-info" role="tabpanel">
                                        <div class="myaccount-content">
                                            <h3>Account Details</h3>
                                            @if (session('status'))
                                                <div class="alert alert-success">
                                                    {{ session('status') }}
                                                </div>
                                            @endif
                                            @if (!Auth::user()->member)
                                                <div class="alert alert-danger">
                                                    <strong>Perhatian !!!</strong> Harap isi alamat lengkap anda
                                                </div>
                                            @endif
                                            <div class="account-details-form">
                                                @if (auth()->user() && auth()->user()->isCustomer())
                                                    @php
                                                        $dest = 'savec';
                                                    @endphp
                                                @else
                                                    @php
                                                        $dest = 'saver';
                                                    @endphp
                                                @endif
                                                    <form action="{{ route('profile.' . $dest)}}" method="POST">
                                                    @method('PATCH')
                                                    @csrf
                                                    <div>
                                                        @if (session()->has('message'))
                                                            <div class="alert alert-success">
                                                                {{ session('message') }}
                                                            </div>
                                                        @endif
                                                    </div>
                                                    <div class="single-input-item">
                                                        <label for="display-name" class="required">Display Name</label>
                                                        <input type="text" id="display-name" name="name" value="{{Auth::user()->name}}" />
                                                    </div>
                                                    <div class="single-input-item">
                                                        <label for="email" class="required">Email Addres</label>
                                                        <input type="email" id="email" name="email" value="{{Auth::user()->email}}" />
                                                    </div>
                                                    <div class="single-input-item">
                                                        <label for="email" class="required">Phone Number</label>
                                                        <input type="text" id="email" name="phone_number" value="{{Auth::user()->member->phone_number ?? ''}}" />
                                                    </div>
                                                    <fieldset>
                                                        <legend>Password change</legend>
                                                        <div class="row">
                                                            <div class="col-lg-6">
                                                                <div class="single-input-item">
                                                                    <label for="new-pwd" class="required">New Password</label>
                                                                    <input type="password" id="new-pwd"  />
                                                                </div>
                                                            </div>
                                                            <div class="col-lg-6">
                                                                <div class="single-input-item">
                                                                    <label for="confirm-pwd" class="required">Confirm Password</label>
                                                                    <input type="password" id="confirm-pwd" name="password"/>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </fieldset>
                                                    <fieldset>
                                                        <legend>Address</legend>
                                                        <div class="single-input-item">
                                                            <div class="checkout-form-list">
                                                                <label class='col-6' for="">Provinsi <div class="lds-ripple" wire:loading wire:target="province"><div></div><div></div></div></label>
                                                                <select class="form-control col-12" name="province_id" id="province" wire:model="province">
                                                                    <option value="selected" selected>--Pilih Provinsi--</option>
                                                                    @foreach ($locations as $location )
                                                                        <option value="{{$location->id}}">{{$location->name}}</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="single-input-item">
                                                            <div class="checkout-form-list">
                                                                <label class='col-6' for="">Kabupaten/Kota <div class="lds-ripple" wire:loading wire:target="city"><div></div><div></div></div></label>
                                                                <select class="form-control col-12" name="city_id" id="city" wire:model="city">
                                                                    <option value="selected" selected>--Pilih Kota--</option>
                                                                    @foreach ($cities as $city)
                                                                        <option value="{{$city->city_id}}">{{$city->name}}</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="single-input-item">
                                                            <div class="checkout-form-list">
                                                                <label class='col-6' for="">Kecamatan <div class="lds-ripple" wire:loading wire:target="subdistrict"><div></div><div></div></div></label>
                                                                <select class="form-control col-12" name="subdistrict_id" id="subdistrict" wire:model="subdistrict">
                                                                    <option value="selected" selected>--Pilih Kecamatan--</option>
                                                                    @foreach ($subdistricts as $subdistrict)
                                                                        <option value="{{$subdistrict->subdistrict_id}}">{{$subdistrict->subdistrict_name}}</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="single-input-item">
                                                            <div class="checkout-form-list">
                                                                <label class='col-6'>Alamat lengkap</label>
                                                                <textarea name="address" id="address" cols="30" rows="7" class="form-control">{{ Auth::user()->member->address ?? '' }}</textarea>
                                                            </div>
                                                        </div>
                                                    </fieldset>
                                                    <div class="single-input-item">
                                                        <button type="submit" class="check-btn sqr-btn">Save Changes</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div> <!-- Single Tab Content End -->
                                    <!-- Single Tab Content Start -->
                                    <div class="tab-pane fade" id="orders" role="tabpanel">
                                        <div class="myaccount-content">
                                            <h3>Orders</h3>
                                            <div class="myaccount-table table-responsive text-center">
                                                <table class="table table-bordered">
                                                    <thead class="thead-light">
                                                        <tr>
                                                            <th>Order</th>
                                                            <th>Date</th>
                                                            <th>Status</th>
                                                            <th>Total</th>
                                                            <th>Action</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach ($transactions as $key => $transaction)
                                                            <tr>
                                                                <td>{{$transaction->invoice}}</td>
                                                                <td>{{$transaction->created_at}}</td>
                                                                <td>
                                                                    @php
                                                                        if ($transaction->status == 0) {
                                                                            $status = 'Pending';
                                                                        }elseif ($transaction->status == 1) {
                                                                            $status = 'Dikemas';
                                                                        }elseif ($transaction->status == 2) {
                                                                            $status = 'Dikirim';
                                                                        }elseif ($transaction->status == 3) {
                                                                            $status = 'Diterima';
                                                                        }elseif ($transaction->status == 4) {
                                                                            $status = 'Selesai';
                                                                        }elseif ($transaction->status == 5) {
                                                                            $status = 'Ditolak';
                                                                        }elseif ($transaction->status == 6) {
                                                                            $status = 'Dibatalkan';
                                                                        }
                                                                    @endphp
                                                                    {{$status}}
                                                                </td>
                                                                <td>Rp. {{number_format($transaction->subtotal)}}</td>
                                                                <td>
                                                                    <a href="{{ route('detail.order'. Auth::user()->getRoleNames()[0], ['transaction'=>$transaction->id])}}" class="check-btn sqr-btn btn btn-success">Detail</a>
                                                                    <a href="{{ route('print.invoice', ['id'=>$transaction->id])}}" class="check-btn sqr-btn btn btn-success">Print Invoice</a>
                                                                    @if ($transaction->status == 0)
                                                                    <a href="https://wa.me/{{$transaction->seller->member->phone_number}}?text={{$transaction->invoice}} dengan total pembayaran {{$transaction->subtotal}}" class="check-btn sqr-btn btn btn-warning">Bayar</a>
                                                                    @elseif ($transaction->status == 1)
                                                                        <button href="#" class="check-btn sqr-btn btn btn-secondary" disabled>Sudah Dibayar</button>
                                                                    @elseif ($transaction->status == 2)
                                                                        <button href="#" class="check-btn sqr-btn btn btn-primary" wire:click='warnDone({{$transaction->id}})'>Diterima</button>
                                                                    @elseif($transaction->status == 4)
                                                                        @if ($transaction->reviewed == 0)
                                                                            <button href="#" class="check-btn sqr-btn btn btn-primary" onclick="setIndex({{$transaction->id}})">Review</button>
                                                                        @endif
                                                                    @endif
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Single Tab Content End -->
                                    <!-- Single Tab Content Start -->
                                    <!-- Single Tab Content End -->
                                    <!-- Single Tab Content Start -->
                                    {{-- <div class="tab-pane fade" id="payment-method" role="tabpanel">
                                        <div class="myaccount-content">
                                            <h3>Payment Method</h3>
                                            <p class="saved-message">You Can't Saved Your Payment Method yet.</p>
                                        </div>
                                    </div> --}}
                                    <!-- Single Tab Content End -->
                                </div>
                            </div> <!-- My Account Tab Content End -->
                        </div>
                    </div> <!-- My Account Page End -->
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="modal_review" tabindex="-1" role="dialog" aria-labelledby="modal_review" aria-hidden="true">
        <div class="modal-dialog" role="document">
          <div class="modal-content">
            <div class="modal-header" style="padding: 15px">
              <h3 class="modal-title">Review</h3>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <form action="" method="POST" id="form-add-review" enctype="multipart/form-data">
                @csrf
                <div class="modal-body row">
                    <div class="form-group col-md-12">
                        <div class="rating"><input type="radio" name="rating" value="5" id="5"><label for="5">☆</label> <input type="radio" name="rating" value="4" id="4"><label for="4">☆</label> <input type="radio" name="rating" value="3" id="3"><label for="3">☆</label> <input type="radio" name="rating" value="2" id="2"><label for="2">☆</label> <input type="radio" name="rating" value="1" id="1"><label for="1">☆</label>
                        </div>
                    </div>
                    <div class="form-group col-md-12">
                        <textarea name="word" cols="30" rows="10" class="form-control"></textarea>
                    </div>
                    <div class="form-group col-md-12">
                        <input type="number" name="transaction_id" class="form-control" id="transaction_review" hidden>
                    </div>
                    <div class="form-group col-md-12">
                        <input type="number" name="buyer_id" class="form-control" id="buyer_review" hidden>
                    </div>
                    <div class="form-group col-md-12">
                        <input type="number" name="member_id" class="form-control" id="member_review" hidden>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>
            </form>
          </div>
        </div>
    </div>
        <script>
            document.addEventListener('livewire:load', function () {
                $( "#province" ).change(function() {
                });

            })
            function setIndex(id) {
                // index = id;
                // console.log(index);
                var url = "{{route('review.edit', ":id")}}";
                url = url.replace(":id", id);
                $.ajax({
                    type: 'GET',
                    url: url,
                    success: function(data) {
                        console.log(data);
                        $('#modal_review').modal('show')
                        $("#transaction_review").val(data.data.id)
                        $("#member_review").val(data.data.seller_id)
                        $("#buyer_review").val(data.data.user_id)
                        var formAction = "{{route('review.store')}}";
                        $("#form-add-review").attr("action", formAction);
                    },
                });
            };

        </script>
</div>


