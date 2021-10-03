@extends('layouts.dashboard')

@section('content')
<div class="section-body">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body overflow-auto">
                    <div class="row">
                        <div class="col-lg-6 col-md-12 mt-2">
                            <span data-toggle="tooltip" data-placement="right" class="badge @if($this_month_total_transaction>$monthly_min_transaction) badge-success @else badge-danger @endif text-center" style="font-size: 14px;font-weight:bold;" title="@if($this_month_total_transaction<$monthly_min_transaction) Anda belum memenuhi minimal transaksi(selesai) per bulan  @else Selamat anda sudah memenuhi transaksi(selesai) per bulan @endif">
                                Total Belanja anda bulan ini adalah {{"Rp " . number_format($this_month_total_transaction,2,',','.')}}
                            </span>

                        </div>
                        <div class="col-lg-6 col-md-12 mt-2">
                            <span data-toggle="tooltip" data-placement="right" class="badge @if($this_month_total_transaction>$monthly_min_transaction) badge-success @else badge-warning @endif text-center" style="font-size: 14px;font-weight:bold;">
                                Minimal Belanja anda perbulan adalah {{"Rp " . number_format($monthly_min_transaction,2,',','.')}}
                            </span>
                        </div>
                        <!-- <div>
                            <button id="showMontlyTransaction" class="btn btn-success btn-sm">Detail</button>

                        </div> -->
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="card">
        <div class="card-header">
            @role('superadmin')
            @else
            @endrole
            <div class="row">
                <div class="input-group mb-3">
                    <div class="input-group mb-3">
                        <select name="product_category" id="product_category" class="form-control" onchange="onchangeProductType(this.value)"></select>
                    </div>
                </div>
            </div>
            <div class="ml-auto">
                <div class="row">
                    <div class="input-group mb-3">
                        <input type="text" class="form-control" oninput="onchangeProductType(this.value)" name="keyword" placeholder="Nama Produk" value="{{ request()->keyword ?? '' }}">
                        <div class="input-group-append">
                            <button class="btn btn-primary"><i class="fas fa-search"></i>Cari</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-body overflow-auto">
            <form action="" method="POST" id="formTambah">
                @csrf

                <input type="hidden" name="ongkir-discount" value="0" readonly>
                <input type="hidden" name="discount" value="0" readonly>
                <div class="" id="table_data">
                    @include('pages.order.order.paginationdistributor')
                </div>
                <div class="row">
                    <div class="col-md-3">
                        <select name="courier" id="courier" class="form-control select2"></select>
                    </div>
                    <div class="col-md-3">
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
                <div class="row">
                    <div class="col-md-6">
                        Subsidi Ongkir
                        <input disabled name="" id="displayOngkirDiscount" class="form-control" value="0">
                    </div>

                    <div class="col-md-6 d-none" id="isUserGetDiscount">
                        Anda berhak mendapatkan diskon sebesar {{$discount_role_based}}%
                        <input disabled name="" id="displayPriceAfterDiscount" class="form-control" value="0">
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
    let discountFromRole = parseInt("{{$discount_role_based}}");
    let thisMonthTotalTransaction = parseInt("{{$this_month_total_transaction}}");
    let monthlyMinimalTransaction = parseInt("{{$monthly_min_transaction}}");
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
        });
        //getcategory
        new Promise((resolve, reject) => {
            $axios.get(`{{ route('api.get_category') }}`)
                .then(({
                    data
                }) => {
                    let html = `<option selected disabled value="first">== Filter Category ==</option>`
                    let dataArr = data.data;
                    dataArr.map((item) => {
                        html += `<option value="${item.id}">${item.name}</option>`

                    })
                    $("#product_category").html(html)
                })
        });

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

    let tempCounter = 0;
    let discount_ongkir = 0;

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
            return 0;
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
                $("#totalSemua").addClass('border-danger')
                $("#syarat").addClass('border-danger')
                switch ("{{$role}}") {
                    case "distributor":

                        break;

                    default:
                        break;
                }
                $("input[name='discount']").val();
            } else {
                $("#totalSemua").removeClass('border-danger')
                $("#totalSemua").addClass('border-success')
                $("#syarat").removeClass('border-danger')
                $("#syarat").addClass('border-success')
            }

            if (uhek + thisMonthTotalTransaction > monthlyMinimalTransaction) {
                $("input[name='discount']").val(discountFromRole);
                console.log("before discount", uhek);

                let tempDiscount = uhek * (discountFromRole / 100);
                $("#displayPriceAfterDiscount").val(`Harga setelah diskon adalah Rp. ${(uhek - tempDiscount).toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,')}`);
                $("#isUserGetDiscount").removeClass("d-none");
                // console.log("after discount", (uhek - tempDiscount));
                // console.log($(`input[name="productCategory${id}"]`).val());
                switch ($(`input[name="productCategory${id}"]`).val()) {
                    case "1":
                        discount_ongkir += 1000;
                        break;
                    case "2":
                        discount_ongkir += 1000;
                        break;
                    case "3":
                        discount_ongkir += 500
                        break;
                    default:
                        break;
                }
                $("input[name='ongkir-discount']").val(discount_ongkir);
                console.log(discount_ongkir);
                $("#displayOngkirDiscount").val(`Rp. ${discount_ongkir.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,')}`);
            } else {
                $("#isUserGetDiscount").addClass("d-none");
                $("#displayPriceAfterDiscount").val(`Rp. ${0}`);
            }
            $("#totalSemuaInt").attr('value', `${uhek}`);
            $("#totalSemua").val("Rp " + uhek.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'));

        }
        let prod_weight = parseInt($(`#field-price-${id}`).data('weight'));
        weight -= prod_weight * (total - 1);
        weight += prod_weight * total;
        $('#inputWeight').val(weight);
    }
    $('html').on('click', '.pagination a', function(e) {
        e.preventDefault();
        console.log($(".pagination a"));
        var url = $(this).attr('href');
        $swal.fire({
            title: 'Perhatian!',
            text: "Pastikan anda sudah memilih semua produk yg anda butuhkan di halaman ini terlebih dahulu",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            cancelButtonText: 'Belum',
            confirmButtonText: 'Sudah!'
        }).then((result) => {
            if (result.isConfirmed) {
                $axios.get(url).then(() => {
                    refresh_table(url);
                });
            }
        });
    });

    function onchangeProductType(id) {
        console.log(isNaN(parseInt(id)));
        let arrayH = !isNaN(parseInt(id)) ? $(".category_product").toArray() : $(".product_name").toArray();
        !isNaN(parseInt(id)) ? categoryFilter(arrayH, id) : nameFilter(arrayH, id);
    }

    function categoryFilter(arrayH, id) {
        let checkMatching = true;
        const warnP = document.createElement('p');
        arrayH.forEach((item) => {
            if (item.value === "first") {
                return;
            }
            if (item.value !== id) {
                item.parentElement.classList.add('d-none');
                checkMatching = false;
            } else {
                item.parentElement.classList.remove('d-none');
                checkMatching = true;
            }
            let warnElement = document.getElementById("showWarn");
            if (!checkMatching) {
                if (warnElement === null) {
                    warnP.innerHTML = `<p id="showWarn" class="text-center">Data tidak ditemukan</p>`
                    item.parentElement.parentElement.appendChild(warnP);
                }
            } else {
                if (warnElement) {
                    let tbody = document.getElementById("tbody");
                    tbody.removeChild(warnElement.parentNode);

                }
            }
        });
    }

    function nameFilter(arrayH, id) {
        let checkMatching = [];
        const warnP = document.createElement('p');

        arrayH.map((item) => {
            // console.log("aaaa", );
            if (!(item.innerHTML.toLowerCase().includes(id.toLowerCase()))) {
                item.parentElement.classList.add('d-none');
                checkMatching.push(false);
            } else {
                item.parentElement.classList.remove('d-none');
                checkMatching.push(true);
            }
            let warnElement = document.getElementById("showWarn");
            if (!checkMatching.includes(true)) {
                if (warnElement === null) {
                    warnP.innerHTML = `<p id="showWarn" class="text-center">Data tidak ditemukan</p>`
                    item.parentElement.parentElement.appendChild(warnP);
                }
            } else {
                if (warnElement) {
                    let tbody = document.getElementById("tbody");
                    tbody.removeChild(warnElement.parentNode);
                }
            }
        });
    }
</script>
@endsection