@extends('layouts.dashboard')
{{-- @inject('SettingTrait', 'App\Traits\SettingTrait') --}}

@section('content')
<div class="section-body">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body overflow-auto">
                    <div class="d-flex justify-content-center">
                        <span data-toggle="tooltip" data-placement="bottom" class="badge @if($this_month_total_transaction>$monthly_min_transaction) badge-success @else badge-danger @endif text-center" style="font-size: 14px;font-weight:bold;"  title="@if($this_month_total_transaction<$monthly_min_transaction) Anda belum memenuhi minimal transaksi(selesai) per bulan  @else Selamat anda sudah memenuhi transaksi(selesai) per bulan @endif">
                            Total Belanja anda bulan ini adalah {{"Rp " . number_format($this_month_total_transaction,2,',','.')}}
                        </span>
                        <!-- <div>
                            <button id="showMontlyTransaction" class="btn btn-success btn-sm">Detail</button>

                        </div> -->
                    </div>
                </div>
            </div>
        </div>
    </div>
        <div class="card">

            <div class="card-body overflow-auto">
                <form action="" method="POST" id="formTambah">
                    @csrf
                    <div class="">
                        <table class="table product-table text-center">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Gambar</th>
                                    <th>Nama Produk</th>
                                    <th>Harga</th>
                                    <th>Jumlah</th>
                                    <th>Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                <input type="hidden" value="0" id="inputWeight">
                                @forelse ($products as $key => $value)
                                <input type="hidden" name="id[]" value="{{ $value->product_id }}">
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td><img src="{{ asset('upload/product').'/'.$value->product->image }}" alt="{{ $value->product->image }}" class="img-fluid" width="200"></td>
                                    <td>{{ $value->product->name }}</td>
                                    @if ($discount =
                                    $value->discount()->where('user_id', $value->user_id)->where('status', 1)->first())
                                    @php
                                    $status = true;
                                    if($value->member_price) {
                                    $price = $value->member_price;
                                    } else {
                                    $price = $value->product->price;
                                    }
                                    if($discount) $price = App\Traits\SettingTrait::getDiscount($price, $discount->discount );
                                    $user_discount = $value->discount()->where('user_id', $value->user_id)->where('status', 1)->first();
                                    if($user_discount) {
                                    $price = App\Traits\SettingTrait::getDiscount($price, $user_discount->discount);
                                    }
                                    @endphp
                                    @else
                                    @php
                                    $status = false;
                                    if($value->member_price) {
                                    $price = $value->member_price;
                                    } else {
                                    $price = $value->product->price;
                                    }
                                    @endphp
                                    @endif
                                    <td id="field-price-{{ $value->product_id }}" data-weight="{{ $value->product->weight }}" data-price="{{ $price }}">
                                        @if ($status)
                                        {{-- @if ($value->member_price)
                                            <s>Rp. {{ number_format($value->member_price) }}</s><br>
                                        @else --}}
                                        <s>Rp. {{ number_format($value->product->price) }}</s><br>
                                        {{-- @endif --}}
                                        @endif
                                        Rp. {{ number_format($price) }}
                                    </td>
                                    <td>
                                        <div class="row">

                                            <div class="col-12">
                                                <input name="qty[]" oninput="onchangePrice({{ $value->product_id }})" type="number" id="total-{{ $value->product_id }}" class="form-control qty text-center" value="0" min="0">
                                            </div>

                                        </div>
                                    </td>
                                    <input type="hidden" name="price[]" id="input-total-{{ $value->product_id }}">
                                    <td id="field-total-{{ $value->product_id }}" class="field-total">-</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6">Tidak ada data</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                        <div class="row">
                            <div class="col-md-6">
                                <select name="courier" id="courier" class="form-control select2"></select>
                            </div>
                            <div class="col-md-6">
                                <button type="button" class="btn btn-success" id="btn-courier">Cek Ongkir</button>
                            </div>
                            <div class="col-md-3">
                                Total Belanja
                                <input disabled name="" id="totalSemua" class="form-control">
                                <input name="m" hidden id="totalSemuaInt" class="form-control" value="0">
                            </div>
                            <div class="col-md-3">
                                Minimal Belanja({{Auth::user()->getRoleNames()->first()}})
                                <input disabled name="" id="syarat" class="form-control" value="{{"Rp " . number_format($minimal_transaction,2,',','.')}}">
                            </div>
                            <div class="col-md-12 mt-2" id="fieldCourier">
                            </div>
                        </div>
                    </div>
                    <button class="btn btn-success float-right" id="btn-simpan">Order</button>
                </form>
            </div>
        </div>
    </div>
    @endsection

    @section('css')
    <link rel="stylesheet" href="{{ asset('vendor/select2/css/select2.min.css') }}">
    <style>
        button:disabled {
            cursor: not-allowed;
            pointer-events: all !important;
        }

        ul.timeline {
            list-style-type: none;
            position: relative;
        }

        ul.timeline:before {
            content: ' ';
            background: #d4d9df;
            display: inline-block;
            position: absolute;
            left: 29px;
            width: 2px;
            height: 100%;
            z-index: 400;
        }

        ul.timeline>li {
            margin: 20px 0;
            padding-left: 20px;
        }

        ul.timeline>li:before {
            content: ' ';
            background: white;
            display: inline-block;
            position: absolute;
            border-radius: 50%;
            border: 3px solid #22c0e8;
            left: 20px;
            width: 20px;
            height: 20px;
            z-index: 400;
        }
    </style>
    @endsection


    @section('js')
    <script src="{{ asset('vendor/select2/js/select2.min.js') }}"></script>
    <script>
        let weight = 0
        var minTransation = parseInt("{{$minimal_transaction}}")
        var totalNominal = [];
        // var uhek = 0;
        $(document).ready(function() {
            $("#formTambah").on('submit', (e) => {
                e.preventDefault()
                let countProducts = parseInt($('#totalSemuaInt').val());
                let serializedData = $("#formTambah").serialize()
                if (countProducts < minTransation) {
                    return $swal.fire({
                        icon: 'error',
                        title: "Gagal",
                        text: "Perhatikan minimal transaksi anda"
                    })
                }
                if (!$('input[name="cost" ]:checked').val()) {
                    return $swal.fire({
                        icon: 'error',
                        title: "Gagal",
                        text: "Anda belum memilih jenis ongkos kirim"
                    })
                }
                new Promise((resolve, reject) => {
                    $axios.post(`{{ route('order.store') }}`, serializedData)
                        .then(({
                            data
                        }) => {
                            $('#modal_tambah').modal('hide')
                            refresh_table(URL_NOW)
                            $swal.fire({
                                icon: 'success',
                                title: data.message.head,
                                text: data.message.body
                            })
                            window.location.href = data.redirect
                        })
                        .catch(err => {
                            throwErr(err)
                        })
                })
            })

            $(".qty").keydown(function(e) {
                if (((e.keyCode < 48) || (e.keyCode > 57)) && e.keyCode != 8) {
                    e.preventDefault()
                }
            })

            $("#courier").select2()
            $("#btn-simpan").attr('disabled', 'disabled')

            new Promise((resolve, reject) => {
                $axios.post(`{{ route('api.check_home') }}`, {
                        api_token: `{{ auth()->user()->api_token }}`
                    })
                    .catch(err => {
                        $swal.fire({
                            icon: 'error',
                            title: err.response.data.message.head,
                            text: err.response.data.message.body,
                        })
                        $("#btnTambah").attr('disabled', 'disabled')
                    })
            })

            new Promise((resolve, reject) => {
                $axios.get(`{{ route('api.get_courier') }}`)
                    .then(({
                        data
                    }) => {
                        let html = `<option selected disabled value="">== Pilih Kurir ==</option>`
                        $.each(data, (i, e) => {
                            html += `<option value="${e.name}">${e.name}</option>`
                        })
                        $("#courier").html(html)
                    })
            })

            $("#btn-courier").on('click', () => {
                let local_weight = $("#inputWeight").val()
                if (local_weight <= 0) {
                    return $swal.fire({
                        icon: 'error',
                        title: "Gagal",
                        text: "Anda belum memilih produk"
                    })
                }
                let courier = $("#courier").val();
                if (!courier) {
                    return $swal.fire({
                        icon: 'error',
                        title: "Gagal",
                        text: "Anda belum memilih kurir"
                    })
                }
                loading('show', $("#btn-courier"));
                new Promise((resolve, reject) => {
                    $axios.post(`{{ route('api.get_ongkir') }}`, {
                            'api_token': `{{ auth()->user()->api_token }}`,
                            'origin': `{{ $upper_origin['subdistrict'] ?? '' }}`,
                            'destination': `{{ auth()->user()->member->subdistrict_id ?? '' }}`,
                            'weight': local_weight,
                            'courier': courier
                        })
                        .then(({
                            data
                        }) => {
                            if (data.status.code == 200) {
                                let result = data.results
                                let cost = result[0].costs
                                let html = ``
                                $.each(cost, (i, e) => {
                                    let teks = e.service
                                    html += `<div class="custom-control custom-radio">
                    <input type="radio" id="radius-${i}" name="cost" class="custom-control-input"
                        value="${e.cost[0].value}">
                    <label class="custom-control-label" for="radius-${i}">${teks} - Rp.
                        ${(e.cost[0].value).toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,')} - ${e.cost[0].etd} Hari</label>
                </div>`
                                })
                                $("#fieldCourier").html(html)
                                $("#btn-simpan").prop("disabled", false)
                            } else {
                                $swal.fire({
                                    icon: 'error',
                                    title: "Ops",
                                    text: "Ops"
                                })
                            }
                            loading('hide', $("#btn-courier"))
                        })
                        .catch(err => {
                            $swal.fire({
                                icon: 'error',
                                title: "Ops",
                                text: "Ops"
                            })
                            loading('hide', $("#btn-courier"))
                        })
                })
            })
        });

        let tempCounter = 0;

        function onchangePrice(id) {
            let total = parseInt($(`#total-${id}`).val())
            // console.log(total)
            if (total === "NaN") {
                $(`#total-${id}`).val(1);
                return 0;
            }
            // console.log(parseInt($(`#input-total-${id}`).val())
            // totalNominal += parseInt($(`#input-total-${id}`).val());
            // if (total > max - 1) {
            // return $swal.fire('Gagal', 'Stock hanya ' + max, 'error')
            // }
            let price = parseInt($(`#field-price-${id}`).data('price'))
            // let new_total = total + 1
            $(`#total-${id}`).val(total)
            let html = (total * price).toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,')
            if (html == "NaN") {
                $(`#field-total-${id}`).html(`Rp. -`)
                $(`#input-total-${id}`).val(`-`)
            } else {
                $(`#field-total-${id}`).html(`Rp. ${html}`)
                $(`#input-total-${id}`).val(`${total*price}`)
                // if (totalNominal[id]) {
                totalNominal[id] = total * price;
                var uhek = 0;

                for (let i = 0; i < totalNominal.length; i++) {
                    if (typeof totalNominal[i] == "number") {
                        uhek += totalNominal[i];
                    }
                }
                if (uhek < minTransation) {
                    $("#totalSemua").addClass('border-danger');

                    $("#syarat").addClass('border-danger');
                    // switch ("{{$role}}") { 
                    //     case "distributor" :
                    //          break; 
                    //          default:
                    //              break; 
                    //             } 
                    $("input[name='discount']").val();
                } else {
                    $("#totalSemua").removeClass('border-danger');
                    $("#totalSemua").addClass('border-success');
                    $("#syarat").removeClass('border-danger');
                    $("#syarat").addClass('border-success');
                }
                console.log(uhek);
                $("#totalSemuaInt").attr('value', `${uhek}`);
                $("#totalSemua").val("Rp " + uhek.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'));

            }
            let prod_weight = parseInt($(`#field-price-${id}`).data('weight'));
            weight -= prod_weight * (total - 1);
            weight += prod_weight * total;
            $('#inputWeight').val(weight);
        }
        // }
    </script>
    @endsection