<div>
    <!-- Breadcrumb Area Start -->
    <div class="breadcrumb-area bg-12 text-center">
        <div class="container">
            <h1>Transaction Detail</h1>
            <nav aria-label="breadcrumb">
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="index.html">Home</a></li>
                    <li class="breadcrumb-item"><a href="index.html">Transaction Detail</a></li>
                    <li class="breadcrumb-item active" aria-current="page">{{$invoice[0]['invoice']}}</li>
                </ul>
            </nav>
        </div>
    </div>
    <div class="container w-100">
        <div class="card">
            <div class="card-header">
                {{$invoice[0]['invoice']}}
            </div>
            <div class="card-body">
              <table>
                  <tr>
                      <td>Nama</td>
                      <td>{{$invoice[0]['user']['name']}}</td>
                  </tr>
              </table>
            </div>
          </div>
    </div>
    <!-- Breadcrumb Area End -->
    <!-- Cart Area Start -->
    <div class="cart-area table-area pt-110 pb-95">
        <div class="container">
                @php
                    if ($invoice[0]['status'] == 0) {
                        $text = 'text-secondary';
                        $status = 'Pending';
                    }elseif ($invoice[0]['status'] == 1) {
                        $text = 'text-warning';
                        $status = 'Dikemas';
                    }elseif ($invoice[0]['status'] == 2) {
                        $text = 'text-warning';
                        $status = 'Dikirim';
                    }elseif ($invoice[0]['status'] == 3) {
                        $text = 'text-warning';
                        $status = 'Diterima';
                    }elseif ($invoice[0]['status'] == 4) {
                        $text = 'text-success';
                        $status = 'Selesai';
                    }elseif ($invoice[0]['status'] == 5) {
                        $text = 'text-danger';
                        $status = 'Ditolak';
                    }elseif ($invoice[0]['status'] == 6) {
                        $text = 'text-danger';
                        $status = 'Dibatalkan';
                    }
                @endphp
                <h4 class="{{$text}}">Status Transaksi : {{$status}}</h4>
            <div class="table-responsive">
                <table class="table product-table text-center">
                    <thead>
                        <tr>
                            <th class="table-image">image</th>
                            <th class="table-p-name">product</th>
                            <th class="table-p-price">price</th>
                            <th class="table-p-qty">quantity</th>
                            <th class="table-total">total</th>
                        </tr>
                    </thead>
                    <tbody>
                            @foreach ($transactions as $item )
                                <tr>
                                    <td class="table-image"><a href="product-details.html"><img src="{{asset('upload/product/'. $item->stock->product->picture->first()->image)}}" alt="tidak ada gambar"></a></td>
                                    <td class="table-p-name"><a href="product-details.html">{{$item->stock->product->name}}</a></td>
                                    <td class="table-p-price"><p>Rp.{{ number_format($item->stock->product->price) }}</p></td>
                                    <td class="table-p-qty">
                                        <span>{{$item->qty}}</span>
                                    </td>
                                    <td class="table-total">
                                        <p>Rp.{{ number_format($item->qty*$item->stock->product->price) }}</p>
                                    </td>
                                </tr>
                            @endforeach
                    </tbody>
                </table>
            </div>
            @if ($result)
                <div class="container" style="padding: 30px">
                    <h4>Lacak Resi - <strong>{{$this->waybill . " via " . strtoupper($this->courier)}}</strong></h4>
                    <ul class="timeline" id="fieldTimeline">
                        @foreach ($result['manifest'] as $manifest )
                        <li>
                            <a href="javascript:void(0)">{{$manifest['manifest_date'] . " | " . $manifest['manifest_time']}}</a>
                            <p>{{$manifest['manifest_description'] . " di " . $manifest['city_name']}}</p>
                        </li>
                        @endforeach
                    </ul>
                </div>
            @endif
        </div>
    </div>
    <!-- Cart Area End -->
</div>
