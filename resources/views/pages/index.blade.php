@extends('layouts.dashboard')

@section('content')
<div class="section-body">
    <div class="row">
        @php
        $col = "col";
        @endphp
        @role('superadmin')
        @php
        $col = "col-6";
        @endphp
        <div class="col-12 mb-2">
            <select name="roles" id="roles" class="form-control" autocomplete="off">
                <option value="" selected disabled>== Pilih Level ==</option>
                @foreach ($all_role as $value)
                <option value="{{ $value->name }}">{{ $value->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-6">
            <select name="select_user" id="select_user" class="form-control" autocomplete="off">
                <option value="">Semua User</option>
            </select>
        </div>
        @endrole
        <div class="{{ $col }}">
            <select name="year" id="year" class="form-control mb-2" autocomplete="off">
                @php
                $end_year = \Carbon\Carbon::now()->year+1
                @endphp
                @for ($i = 2019; $i < $end_year; $i++) <option data-year="{{ $i }}" data-id="{{ $i }}" class="tahun" {{ date('Y') == $i ? 'selected' : '' }}>{{ $i }}</option>
                    @endfor
            </select>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12">
            <div class="card card-statistic-2 first">
                <div class="card-stats p-3">
                    <div class="card-stats-title">Penjualan -
                        <div class="dropdown d-inline">
                            <a class="font-weight-600 dropdown-toggle" data-toggle="dropdown" href="#" id="orders-month">{{ $month[(date('n') - 1)] }}</a>
                            <ul class="dropdown-menu dropdown-menu-sm">
                                <li class="dropdown-title">Select Month</li>
                                @foreach ($month as $key => $value)
                                <li>
                                    <a href="#" data-month="{{ $value }}" data-id="{{ $key + 1 }}" class="dropdown-item bulan {{ ($key == (date('n') - 1)) ? 'active' : '' }}">{{ $value }}</a>
                                </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-4 col-md-6 col-sm-12">
                            <div class="card-icon shadow-primary bg-primary">
                                <i class="fas fa-archive"></i>
                            </div>
                            <div class="card-wrap">
                                <div class="card-header">
                                    <h4>Total Penjualan</h4>
                                </div>
                                <div class="card-body" id="countTotal">
                                    0
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-8 col-md-12 col-sm-12 d-flex align-items-center justify-content-between flex-wrapukm">
                            {{-- <div class="card-stats-items"> --}}
                            <div class="card-stats-item">
                                <div class="card-stats-item-count" id="countPending">0</div>
                                <div class="card-stats-item-label">Menunggu</div>
                            </div>
                            <div class="card-stats-item">
                                <div class="card-stats-item-count" id="countShipping">0</div>
                                <div class="card-stats-item-label">Dikirim</div>
                            </div>
                            <div class="card-stats-item">
                                <div class="card-stats-item-count" id="countCompleted">0</div>
                                <div class="card-stats-item-label">Selesai</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-6 col-md-6 col-sm-12" id="fieldPendapatan">
            <div class="card card-statistic-2">
                <div class="card-chart p-4" id="fieldChartPendapatan">
                    <canvas id="balance-chart" height="80"></canvas>
                </div>
                <div class="card-icon shadow-primary bg-primary">
                    <i class="fas fa-dollar-sign"></i>
                </div>
                <div class="card-wrap">
                    <div class="card-header">
                        <h4>Pendapatan</h4>
                    </div>
                    <div class="card-body" id="countPendapatan">
                        0
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-6 col-md-6 col-sm-12" id="fieldPenualan">
            <div class="card card-statistic-2">
                <div class="card-chart p-4" id="fieldChartPenjualan">
                    <canvas id="sales-chart" height="80"></canvas>
                </div>
                <div class="card-icon shadow-primary bg-primary">
                    <i class="fas fa-shopping-bag"></i>
                </div>
                <div class="card-wrap">
                    <div class="card-header">
                        <h4>Penjualan</h4>
                    </div>
                    <div class="card-body" id="countPenjualan">
                        0
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@role('superadmin')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h4>Ranking 10 Besar</h4>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-12 mb-2">
                        <select name="roles" id="rankRole" class="form-control" autocomplete="off">
                            <option value="">Semua Level</option>
                            @foreach ($all_role as $value)
                            <option value="{{ $value->name }}">{{ $value->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-6">
                        <select name="month" id="rankMonth" class="form-control" autocomplete="off">
                            @for ($i = 1; $i < 13; $i++) <option value="{{ $i }}" {{ date('n') == $i ? 'selected' : '' }}>{{ date("F", mktime(0, 0, 0, $i, 10)) }}</option>
                                @endfor
                        </select>
                    </div>
                    <div class="col-6">
                        <select name="year" id="rankYar" class="form-control mb-2" autocomplete="off">
                            @php
                            $end_year = \Carbon\Carbon::now()->year+1
                            @endphp
                            @for ($i = 2019; $i < $end_year; $i++) <option data-year="{{ $i }}" data-id="{{ $i }}" class="tahun" {{ date('Y') == $i ? 'selected' : '' }}>{{ $i }}</option>
                                @endfor
                        </select>
                    </div>
                    <div class="col-12 mt-3" id="fieldRanking">
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
@endrole

@unlessrole('superadmin')
@unlessrole('reseller')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h4>Total Point : <?= $user->total_point ?></h3>
                    </div>
                    <div>
                        <a href="{{ route('page.reward.index') }}" class="btn btn-success">Tukarkan Point / Lihat History Point</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h4>{{$checkMitraRequirement?"Selamat anda masih memenuhi minimal pembelanjaan sebagai
                            $role":"Anda tidak memenuhi minimal pembelanjaan sebagai $role"}}</h3>
                    </div>
                    <div>
                        <button id="showMontlyTransaction" class="btn {{$checkMitraRequirement?'btn-success':'btn-danger'}} btn-sm">Detail</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endrole
@endrole

<div class="row">
    <div class="col-md-12">
        @if(!Auth::user()->isReseller())
        <div class="card">
            <div class="card-header">
                <h4>Pesanan</h4>
                <div class="card-header-action">
                    <a href="{{ route('order.index') }}" class="btn btn-danger">Lainnya <i class="fas fa-chevron-right"></i></a>
                </div>
            </div>
            <div class="card-body p-0 overflow-auto">
                <div class="table-responsive table-invoice" id="table_data">
                    @include("pages.paginationds")
                </div>
            </div>
        </div>
        @else
        <div class="card">
            <div class="card-header">
                <h4>Pesanan Terbaru</h4>
                <div class="card-header-action">
                    <a href="#" class="btn btn-danger">Lainnya <i class="fas fa-chevron-right"></i></a>
                </div>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive table-invoice">
                    <table class="table table-striped" id="table_data">
                        <tr>
                            <th>Invoice ID</th>
                            <th>Customer</th>
                            <th>Status</th>
                            <th>Tanggal Pesan</th>
                            <th>Action</th>
                        </tr>
                        @forelse ($orders as $key => $value)
                        <tr>
                            <td>{{ $value->invoice }}</td>
                            <td>{{ $value->user->name }}</td>
                            <td>
                                @if ($value->status == 0)
                                <span class="badge badge-warning">Pending</span>
                                @elseif($value->status == 1)
                                <span class="badge badge-secondary">Dikemas</span>
                                @elseif($value->status == 2)
                                <span class="badge badge-primary">Dikirim</span>
                                @elseif($value->status == 3)
                                <span class="badge badge-info">Diterima</span>
                                @elseif($value->status == 4)
                                <span class="badge badge-success">Selesai</span>
                                @elseif($value->status == 5)
                                <span class="badge badge-danger">Ditolak</span>
                                @elseif($value->status == 6)
                                <span class="badge badge-danger">Batal</span>
                                @endif
                            </td>
                            <td>{{ Carbon\Carbon::parse($value->created_at)->isoFormat('dddd, D MMMM Y') }}</td>
                            <td><a href="{{ route('detail.order'. Auth::user()->getRoleNames()[0], ['transaction'=>$value->id])}}" class="btn btn-primary btn-sm">Detail</a></td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center">Tidak Ada Pesanan</td>
                        </tr>
                        @endforelse
                    </table>
                </div>
            </div>
        </div>
        @endif
    </div>
</div>
</div>
@endsection

@section('modal')
<div class="modal fade" id="modal-detail" tabindex="-1" role="dialog" aria-labelledby="modal-detailLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modal-detailLabel">Order</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <label for="">Nomor Invoice</label>
                <h4 class="text-success" id="fieldInvoice"></h4>
                <label for="">Status</label>
                <h5 id="fieldStatus"><strong></strong></h5>
                <label for="">Tanggal Order</label>
                <h5 id="fieldTanggalOrder"></h5>
                <hr>
                <p><strong>Daftar Produk</strong></p>
                <div class="row" id="fieldDaftarProduk"></div>
                <div id="checkKirim" style="display: none">
                    <hr>
                    <p><strong>Pengiriman</strong></p>
                    <p id="filedKurir">SiCepat - HALU (Estimasi tiba 21 - 22 Apr)</p>
                    <p>No. Resi: <span id="fieldResi">000495192657</span></p>
                    <ul class="list-unstyled">
                        <li>Dikirim kepada <span id="fieldDikirimKepada" class="text-bold">Kukuh Rahmadani</span></li>
                        <li><span id="fieldAlamat">Jl. Kerto Raharjo, Kec. Lowokwaru, Kota Malang, Jawa Timur, 65144
                                [Tokopedia Note: Jl kertoraharjo 23A, ketawanggede kec lowokwaru] Lowokwaru Kota Malang,
                                65144</span></li>
                        <li><span id="fieldProvinsi">Jawa Timur</span></li>
                        <li>Telp <span id="fieldTelp">083845257534</span></li>
                    </ul>
                    <hr>
                    <p><strong>Status Pengiriman</strong></p>
                    <ul class="timeline" id="fieldTimeline"></ul>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-primary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modal-detail-monthly" tabindex="-2" role="dialog" aria-labelledby="modal-detail-monthly" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modal-detail-monthly">Order</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="card-body p-0 overflow-auto">
                    <div class="table-responsive table-invoice">
                        <table class="table table-striped" id="table_data">
                            <tr>
                                <th>Bulan</th>
                                <th>Total Transaksi</th>
                                <th>Status</th>
                            </tr>
                            @foreach ($monthly_transaction as $index => $item)
                            <tr>
                                <td>{{ $month[$index - 1] }}</td>
                                <td>{{"Rp " . number_format($item["sums"],2,',','.')}}</td>
                                <td>
                                    <i class="  @if ($item['sums'] > $minimal_transaction)
                                                      fas fa-thumbs-up text-success
                                                      @else fas fa-thumbs-down text-danger
                                                  @endif" style="font-size: 20px"></i>
                                </td>
                            </tr>
                            @endforeach
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-primary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('css')
<link rel="stylesheet" href="{{ asset('vendor/chats-js/Chart.min.css') }}">
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
<script src="{{ asset('vendor/chart.js/dist/Chart.min.js') }}"></script>
<script>
    let selected_month = `{{ date('n') }}`
    let selected_year = `{{ date('Y') }}`
    const IS_RESELLER = `{{ Auth::user()->isReseller() }}`
    $(document).ready(() => {
        get_penjualan(`{{ date('n') }}`, `{{ date('Y') }}`)

        loading('show', '#fieldPendapatan')
        get_status_penjualan(`{!! json_encode($hirarki) !!}`, IS_RESELLER)
            .then(() => loading('hide', '#fieldPendapatan'))
        // .catch(() => loading('hide', '#fieldPendapatan'))
        loading('show', '#fieldPenualan')
        get_grafik_penjualan(`{!! json_encode($hirarki) !!}`, IS_RESELLER)
            .then(() => loading('hide', '#fieldPenualan'))
        // .catch(() => loading('hide', '#fieldPenualan'))

        @role('superadmin')
        $("#select_user").on('change', () => {
            let URL_USER = `{{ route('dashboard', ['id' => ':id']) }}`
            URL_USER = URL_USER.replace(':id', $("#select_user").val())
            window.location.href = URL_USER
        })

        $("#roles").on('change', e => {
            let role = $('#roles').val()
            new Promise((resolve, reject) => {
                $axios.post(`{{ route('api.get_user_by_role') }}`, {
                        role
                    })
                    .then(res => {
                        let data = res.data.data
                        let html = `<option value="">Semua User</option>`
                        data.forEach(element => {
                            html += `<option value="${element.id}">${element.name}</option>`
                        })
                        $("#select_user").html(html)

                        loading('show', '#fieldPendapatan')
                        get_status_penjualan(role, IS_RESELLER)
                            .then(() => loading('hide', '#fieldPendapatan'))
                        loading('show', '#fieldPenualan')
                        get_grafik_penjualan(role, IS_RESELLER)
                            .then(() => loading('hide', '#fieldPenualan'))
                    })
            })
        })

        loading('show', '#fieldRanking')
        get_rank(null, selected_month, selected_year)
            .then(() => loading('hide', '#fieldRanking'))

        $("#rankRole,#rankMonth,#rankYar").on('change', () => {
            loading('show', '#fieldRanking')
            let role = $("#rankRole").val()
            let month = $("#rankMonth").val()
            let year = $("#rankYar").val()
            get_rank(role, month, year)
                .then(() => loading('hide', '#fieldRanking'))
        })
        @endrole
    })

    $("#showMontlyTransaction").on("click", () => {
        $("#modal-detail-monthly").modal("show");
    })
    const get_penjualan = (month, year) => {
        return new Promise((resolve, reject) => {
            $axios.post(`{{ route('api.get_penjualan') }}`, {
                    hirarki: `{!! json_encode($hirarki) !!}`,
                    user: `{{Auth::user()->isReseller()}}`,
                    month: `${month}`,
                    year: `${year}`
                })
                .then(({
                    data
                }) => {
                    $("#countPending").html(data.data.pending)
                    $("#countShipping").html(data.data.shipping)
                    $("#countCompleted").html(data.data.completed)
                    let total = parseInt(data.data.pending) + parseInt(data.data.shipping) + parseInt(data.data.completed)
                    $("#countTotal").html(total)
                    resolve(data)
                })
        })
    }

    const get_status_penjualan = (hirarki, user) => {
        return new Promise((resolve, reject) => {
            $axios.post(`{{ route('api.get_status_penjualan') }}`, {
                    hirarki,
                    user
                })
                .then(({
                    data
                }) => {
                    console.log(data.data);
                    $("#countPenjualan").html(data.data.penjualan)
                    // $("#countPendapatan").html()
                    let pendapatan = parseInt(data.data.pendapatan)
                    if (!pendapatan) {
                        pendapatan = 0
                    }
                    $("#countPendapatan").html(`Rp. ${(pendapatan).toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,')}`)
                    resolve(data)
                })
        })
    }

    const get_grafik_penjualan = (hirarki, user) => {
        return new Promise((resolve, reject) => {
            $axios.post(`{{ route('api.get_grafik_penjualan') }}`, {
                    hirarki,
                    user
                })
                .then(({
                    data
                }) => {
                    let grafik = data.data
                    let data_penjualan = []
                    let data_pendapatan = []

                    let label = []

                    $.each(grafik, (index, element) => {
                        data_penjualan.push(element.penjualan)
                        data_pendapatan.push(element.pendapatan)
                        label.push(element.date)
                    })
                    render_grafik('balance-chart', label, data_pendapatan)
                    render_grafik('sales-chart', label, data_penjualan)
                    resolve(data)
                })
        })
    }

    $(".bulan").on('click', e => {
        let comp = $(".bulan")
        let year = $('#year').val()
        $.each(comp, (index, element) => {
            $(element).removeClass('active')
        })
        $(e.currentTarget).addClass("active")
        $("#orders-month").html($(e.currentTarget).data('month'))
        selected_month = $(e.currentTarget).data('id')
        loading('show', $(".card.card-statistic-2.first"))
        get_penjualan($(e.currentTarget).data('id'), year)
            .then(() => loading('hide', $(".card.card-statistic-2.first")))
    })

    $("#year").on('change', () => {
        loading('show', $(".card.card-statistic-2.first"))
        let year = $('#year').val()
        get_penjualan(selected_month, year)
            .then(() => loading('hide', $(".card.card-statistic-2.first")))
    })

    const detail = id => {
        loading('show', $("#table_data"), {
            image: '',
            text: 'Loading....'
        })
        new Promise((resolve, reject) => {
            let url = `{{ route('order.show', ['order' => ':id']) }}`
            url = url.replace(':id', id)
            $axios.get(`${url}`)
                .then(({
                    data
                }) => {
                    $("#fieldInvoice").html(data.data.invoice)
                    let status = data.data.status
                    let textStatus = ''
                    if (status == 0) {
                        textStatus = 'Pending'
                    } else if (status == 1) {
                        textStatus = 'Dikemas'
                    } else if (status == 2) {
                        textStatus = 'Dikirim'
                    } else if (status == 3) {
                        textStatus = 'Diterima'
                    } else if (status == 4) {
                        textStatus = 'Selesai'
                    } else if (status == 5) {
                        textStatus = 'Ditolek'
                    } else if (status == 6) {
                        textStatus = 'Batal'
                    }
                    $("#fieldStatus").html(textStatus)
                    $("#fieldTanggalOrder").html(data.data.tanggal_pesan)

                    let url_asset = `{{ asset('/upload/product') }}`
                    html = ``
                    $.each(data.data.details, (index, element) => {
                        let total_harga = parseInt(element.price) * parseInt(element.qty)
                        html += `<div class="col-md-6">
                                    <img src="${url_asset}/${element.product.image}" alt="gambar" class="img-fluid img-thumnail w-20" class="float-left">
                                    <p>${element.product.name}</p>
                                    <p>${element.qty} Produk (${element.product.weight}) x Rp ${element.price}</p>
                                </div>
                                <div class="col-md-6" style="border-left: 1px solid gray;">
                                    <p><strong>Harga Barang</strong></p>
                                    <p>Rp. ${total_harga}</p>
                                </div>`
                    })
                    $("#fieldDaftarProduk").html(html)

                    if (data.data.waybill) {
                        $("#checkKirim").show()
                        $("#fieldAlamat").html(data.data.user.member.address)
                        $("#fieldProvinsi").html(data.data.user.member.city.province)
                        $("#fieldDikirimKepada").html(data.data.user.name)
                        $("#filedKurir").html(data.data.shipping)
                        $("#fieldResi").html(data.data.waybill)
                        $("#fieldTelp").html(data.data.user.member.phone_number)
                        $("#fieldTimeline").html(``)
                    } else {
                        $("#checkKirim").hide()
                    }
                    if (data.data.waybill) {
                        lacak_resi(data.data.shipping, data.data.waybill)
                            .then(() => {
                                $("#modal-detail").modal('show')
                                loading('hide', $("#table_data"), {
                                    image: '',
                                    text: 'Loading....'
                                })
                            })
                    } else {
                        $("#modal-detail").modal('show')
                        loading('hide', $("#table_data"), {
                            image: '',
                            text: 'Loading....'
                        })
                    }
                })
        })
    }

    const lacak_resi = (kurir, resi) => {
        return new Promise((resolve, reject) => {
            $axios.post(`{{ route('api.lacak_ongkir') }}`, {
                    'waybill': resi,
                    'courier': kurir
                })
                .then(({
                    data
                }) => {
                    if (data.status.code == 400) {
                        $("#fieldTimeline").html("Resi tidak ditemukan")
                    } else {
                        let lacak = data['result']['manifest']
                        let html = ``
                        $.each(lacak, (index, element) => {
                            html += `<li>
                                        <a href="javascript:void(0)">${element['manifest_date']} | ${element['manifest_time']}</a>
                                        <p>${element['manifest_description']}</p>
                                    </li>`
                        })
                        $("#fieldTimeline").html(html)
                    }
                    resolve(data)
                })
                .catch(err => {
                    reject(err)
                })
        })
    }

    const render_grafik = (element, label, data) => {
        if (element == "sales-chart") {
            $('#sales-chart').remove()
            $("#fieldChartPenjualan").append(`<canvas id="sales-chart" height="80"></canvas>`)
        } else if (element == "balance-chart") {
            $('#balance-chart').remove()
            $("#fieldChartPendapatan").append(`<canvas id="balance-chart" height="80"></canvas>`)
        }
        let balance_chart = document.getElementById(`${element}`).getContext('2d');
        let balance_chart_bg_color = balance_chart.createLinearGradient(0, 0, 0, 70);
        balance_chart_bg_color.addColorStop(0, 'rgba(63,82,227,.2)');
        balance_chart_bg_color.addColorStop(1, 'rgba(63,82,227,0)');

        let myChart = new Chart(balance_chart, {
            type: 'line',
            data: {
                labels: label,
                datasets: [{
                    data: data,
                    backgroundColor: balance_chart_bg_color,
                    borderWidth: 3,
                    borderColor: 'rgba(63,82,227,1)',
                    pointBorderWidth: 3,
                    pointBorderColor: 'rgba(63,11,227,1)',
                    pointRadius: 3,
                    pointBackgroundColor: 'rgba(63,90,227,1)',
                    pointHoverBackgroundColor: 'rgba(63,82,227,1)',
                }]
            },
            options: options_grafik
        });
        // myChart.destroy()
    }

    const get_rank = (level = null, month, year) => {
        return new Promise((resolve, reject) => {
            $axios.post(`{{ route('api.get_rank') }}`, {
                    level,
                    month,
                    year
                })
                .then(({
                    data
                }) => {
                    $("#fieldRanking").html(data)
                    resolve(data)
                })
        })
    }
    $('html').on('click', '.pagination a', function(e) {
        e.preventDefault();
        // console.log($(".pagination a"));
        var url = $(this).attr('href');
        // $swal.fire({
        //     title: 'Perhatian!',
        //     text: "Pastikan anda sudah memilih semua produk yg anda butuhkan di halaman ini terlebih dahulu",
        //     icon: 'warning',
        //     showCancelButton: true,
        //     confirmButtonColor: '#3085d6',
        //     cancelButtonColor: '#d33',
        //     cancelButtonText: 'Belum',
        //     confirmButtonText: 'Sudah!'
        // }).then((result) => {
        // if (result.isConfirmed) {
        $axios.get(url).then(() => {
            refresh_table(url);
        });
        // }
        // });
    });
</script>
@endsection