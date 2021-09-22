@extends('layouts.dashboard')

@section('content')
<div class="section-body">
    <div class="card">
        <div class="card-body">
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
                            <input type="hidden" name="id[]" value="{{ $value->id }}">
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td><img src="{{ asset('upload/product').'/'.$value->image }}" alt="{{ $value->image }}" class="img-fluid" width="200"></td>
                                <td>{{ $value->name }}</td>
                                <td id="field-price-{{ $value->id }}" data-weight="{{ $value->weight }}" data-price="{{ $value->price }}">Rp.
                                    {{ number_format($value->price) }}
                                </td>
                                <td>
                                    <input name="qty[]" oninput="onchangePrice({{ $value->id }},1000)" type="number" id="total-{{ $value->id }}" class="form-control qty text-center" value="0" min="0">
                                </td>
                                <input type="hidden" name="price[]" id="input-total-{{ $value->id }}">
                                <td id="field-total-{{ $value->id }}" class="field-total">-</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6">Tidak ada data</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                    <div class="row">
                        <div class="col-md-3">
                            <select name="courier" id="courier" class="form-control select2"></select>
                        </div>
                        <div class="col-md-3">
                            <button type="button" class="btn btn-success" id="btn-courier">Cek Ongkir</button>
                        </div>
                        <div class="col-md-3">
                            Total Belanja
                            <input disabled name="" id="totalSemua" class="form-control" >
                            <input name="m" hidden id="totalSemuaInt" class="form-control" value="0" >
                        </div>
                        <div class="col-md-3">
                            Minimal Belanja({{Auth::user()->getRoleNames()->first()}})
                            <input disabled name="" id="syarat" class="form-control" value="{{"Rp " . number_format($minimal_transaction,2,',','.')}}" >
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
            console.log(countProducts)
            if (countProducts<minTransation) {
                return $swal.fire({
                                icon: 'error',
                                title: "Gagal",
                                text: "Perhatikan minimal transaksi anda"
                            })
            }
            if (!$('input[name="cost"]:checked').val()) {
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

            let courier = $("#courier").val()
            if (!courier) {
                return $swal.fire({
                    icon: 'error',
                    title: "Gagal",
                    text: "Anda belum memilih kurir"
                })
            }
            loading('show', $("#btn-courier"))
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
                                        <input type="radio" id="radius-${i}" name="cost" class="custom-control-input" value="${e.cost[0].value}">
                                        <label class="custom-control-label" for="radius-${i}">${teks} - Rp. ${(e.cost[0].value).toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,')} - ${e.cost[0].etd} Hari</label>
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
    })


    function onchangePrice(id, max) {
        let total = parseInt($(`#total-${id}`).val())
        // console.log(total)
        if (total === "NaN") {
            $(`#total-${id}`).val(1);
            return 0;
        }
        // console.log(parseInt($(`#input-total-${id}`).val())
        // totalNominal += parseInt($(`#input-total-${id}`).val());
        if (total > max - 1) {
            return $swal.fire('Gagal', 'Stock hanya ' + max, 'error')
        }
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
                 uhek +=totalNominal[i];
             }
            }
            if (uhek<minTransation) {
                $("#totalSemua").addClass('border-danger')
                $("#syarat").addClass('border-danger')
            }else{
                $("#totalSemua").removeClass('border-danger')
                $("#totalSemua").addClass('border-success')
                $("#syarat").removeClass('border-danger')
                $("#syarat").addClass('border-success')
            }

            $("#totalSemuaInt").attr('value',`${uhek}`);
            $("#totalSemua").val("Rp "+uhek.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'));
           
        }
        let prod_weight = parseInt($(`#field-price-${id}`).data('weight'))
        weight -= prod_weight * (total - 1)
        weight += prod_weight * total
        $('#inputWeight').val(weight)
    }

    const minus = id => {
        let total = parseInt($(`#total-${id}`).val())
        let price = parseInt($(`#field-price-${id}`).data('price'))
        let prod_weight = parseInt($(`#field-price-${id}`).data('weight'))
        if (total > 0) {
            let new_total = total - 1
            $(`#total-${id}`).val(new_total)
            let html = (new_total * price).toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,')
            $(`#field-total-${id}`).html(`Rp. ${html}`)
            $(`#input-total-${id}`).val(`${new_total*price}`)
            let prod_weight = parseInt($(`#field-price-${id}`).data('weight'))
            weight -= prod_weight
            $('#inputWeight').val(weight)
        }
    }

    const plus = (id, max) => {
        let total = parseInt($(`#total-${id}`).val())
        if (total > max - 1) {
            return $swal.fire('Gagal', 'Stock hanya ' + max, 'error')
        }
        let price = parseInt($(`#field-price-${id}`).data('price'))
        let new_total = total + 1
        $(`#total-${id}`).val(new_total)
        let html = (new_total * price).toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,')
        $(`#field-total-${id}`).html(`Rp. ${html}`)
        $(`#input-total-${id}`).val(`${new_total*price}`)
        let prod_weight = parseInt($(`#field-price-${id}`).data('weight'))
        weight -= prod_weight * (new_total - 1)
        weight += prod_weight * new_total
        $('#inputWeight').val(weight)
    }
</script>
@endsection