@extends('layouts.dashboard')

@section('content')
<div class="section-body">
    <div class="card">
        <div class="card-header">
            @role('superadmin')
            @else
            <button class="btn btn-success" id="btnTambah"><i class="fas fa-plus"></i> Tambah Order</button>
            @endrole
            <button class="btn btn-outline-primary ml-2" onclick="refresh_table(URL_NOW)"><i class="fas fa-sync"></i>Refresh</button>
            <div class="ml-auto">
                <form action="" method="get" class="row">
                    <div class="input-group mb-3">
                        <input type="text" class="form-control" name="keyword" placeholder="Kata Kunci" value="{{ request()->keyword ?? '' }}">
                        <div class="input-group-append">
                            <button class="btn btn-primary"><i class="fas fa-search"></i>Cari</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <div class="card-body table-responsive" id="table_data">
            @include('pages.order.transaction.pagination')
        </div>
    </div>
</div>
@endsection

@section('modal')
<div class="modal fade" tabindex="-1" role="dialog" id="modal_tambah">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalTitle"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="" method="post" id="formTambah">
                @csrf
                <input type="hidden" name="id" id="inputID">
                <div class="modal-body">
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
                                    <td id="field-price-{{ $value->id }}" data-weight="{{ $value->weight }}" data-price="{{ $value->price }}">Rp. {{ number_format($value->price) }}</td>
                                    <td>
                                        <div class="row">
                                            <div class="col-3">
                                                <button type="button" class="btn btn-sm btn-primary" onclick="minus({{ $value->id }})"><i class="fas fa-minus"></i></button>
                                            </div>
                                            <div class="col-6">
                                                <input name="qty[]" type="text" id="total-{{ $value->id }}" class="form-control qty text-center" value="0" min="0" readonly>
                                            </div>
                                            <div class="col-3">
                                                <button type="button" class="btn btn-sm btn-primary" onclick="plus({{ $value->id }})"><i class="fas fa-plus"></i></button>
                                            </div>
                                        </div>
                                    </td>
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
                            <div class="col-md-6">
                                <select name="courier" id="courier" class="form-control select2"></select>
                            </div>
                            <div class="col-md-6">
                                <button type="button" class="btn btn-success" id="btn-courier">Cek Ongkir</button>
                            </div>
                            <div class="col-md-12 mt-2" id="fieldCourier">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-whitesmoke br">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary" id="btn-simpan">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

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
                        <li><span id="fieldAlamat">Jl. Kerto Raharjo, Kec. Lowokwaru, Kota Malang, Jawa Timur, 65144 [Tokopedia Note: Jl kertoraharjo 23A, ketawanggede kec lowokwaru] Lowokwaru Kota Malang, 65144</span></li>
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

<div class="modal fade" id="modal-set-resi" tabindex="-1" role="dialog" aria-labelledby="modal-set-resiLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modal-set-resiLabel">Set Resi</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="" method="POST" id="form-update-resi">
                <input type="hidden" name="id" id="fieldIdUpdateResi">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label for="">Kurir</label>
                        <input type="text" class="form-control" id="fieldCourierUpdate" readonly>
                    </div>
                    <div class="form-group">
                        <label for="">Resi</label>
                        <input type="text" class="form-control" name="waybill" id="fieldResiUpdate">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
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
    let type
    let weight = 0
    const PENDING = 0
    const DIKEMAS = 1
    const DIKIRIM = 2
    const DITERIMA = 3
    const SELESAI = 4
    const DITOLAK = 5
    const BATAL = 6
    $(document).ready(function() {
        // Get All Kategori
        refresh_get_category()

        $("#btnTambah").on('click', () => {
            type = 'STORE'
            $("#modalTitle").html('Tambah Kategori')
            let comp = $(".field-total")
            $.each(comp, (i, e) => {
                let id = e.id
                $(`#${id}`).html("Rp. 0,00")
            })
            $("#formTambah")[0].reset()
            weight = 0
            $("#inputWeight").val(weight)
            $("#fieldCourier").html('')
            $('#modal_tambah').modal('show')
        })

        $("#formTambah").on('submit', (e) => {
            e.preventDefault()
            let serializedData = $("#formTambah").serialize()

            if (!$('input[name="cost"]:checked').val()) {
                return $swal.fire({
                    icon: 'error',
                    title: "Gagal",
                    text: "Anda belum memilih jenis ongkos kirim"
                })
            }

            new Promise((resolve, reject) => {
                $axios.post(`{{ route('transaction.store') }}`, serializedData)
                    .then(({
                        data
                    }) => {
                        $('#modal_tambah').modal('hide')
                        refresh_get_category()
                        refresh_table(URL_NOW)
                        $swal.fire({
                            icon: 'success',
                            title: data.message.head,
                            text: data.message.body
                        })
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

        $("#courier").select2({
            dropdownParent: $("#modal_tambah")
        })
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
                    }).then((res) => {
                        if (res.isConfirmed) {
                            window.location.replace('/dashboard/pengaturan/profile');
                        }
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
                        html += `<option value="${e}">${e}</option>`
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

        $("#form-update-resi").on('submit', e => {
            e.preventDefault()
            new Promise((resolve, reject) => {
                let url = `{{ route('transaction.set_resi', ['id' => ":id"]) }}`
                url = url.replace(':id', $("#fieldIdUpdateResi").val())
                $axios.post(`${url}`, $("#form-update-resi").serialize())
                    .then(({
                        data
                    }) => {
                        $swal.fire({
                            icon: 'success',
                            title: data.message.head,
                            text: data.message.body,
                        })
                        $("#modal-set-resi").modal('hide')
                    })
            })
        })
    })

    const editData = id => {
        new Promise((resolve, reject) => {
            $axios.get(`${URL_NOW}/${id}`)
                .then(({
                    data
                }) => {
                    let category = data.data
                    type = 'UPDATE'
                    $("#formTambah")[0].reset()
                    $("#inputID").val(category.id)
                    $("#modalTitle").html('Update Kategori')
                    if (category.parent_id) $("#selectSubKategori").val(category.parent_id)
                    $("#inputName").val(category.name)
                    $('#modal_tambah').modal('show')
                })
                .catch(err => {
                    console.log(err)
                    $swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Something went wrong!',
                    })
                })
        })
    }

    const deleteData = id => {
        $swal.fire({
                title: 'Yakin?',
                text: "Ingin menghapus data ini!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                cancelButtonText: 'Tidak',
                confirmButtonText: 'Ya!'
            })
            .then((result) => {
                if (result.isConfirmed) {
                    new Promise((resolve, reject) => {
                        $axios.delete(`${URL_NOW}/${id}`)
                            .then(({
                                data
                            }) => {
                                $swal.fire({
                                    icon: 'success',
                                    title: data.message.head,
                                    text: data.message.body
                                })
                                refresh_table(URL_NOW)
                            })
                            .catch(err => {
                                let data = err.response.data
                                $swal.fire({
                                    icon: 'error',
                                    title: data.message.head,
                                    text: data.message.body
                                })
                            })
                    })
                }
            })
    }

    const refresh_get_category = () => {
        new Promise((resolve, reject) => {
            $axios.get(`{{ route('api.get_category') }}`)
                .then(({
                    data
                }) => {
                    let option = '<option value="">== Kosongkan Sub Kategori ==</option>'
                    $.each(data.data, (i, e) => {
                        option += `<option value="${e.id}">${e.name}</option>`
                    })
                    $('#selectSubKategori').html(option)
                })
                .catch(err => {
                    $swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Something went wrong!',
                    })
                })
        })
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
            let prod_weight = parseInt($(`#field-price-${id}`).data('weight'))
            weight -= prod_weight
            $('#inputWeight').val(weight)
        }
    }

    const plus = id => {
        let total = parseInt($(`#total-${id}`).val())
        let price = parseInt($(`#field-price-${id}`).data('price'))
        let new_total = total + 1
        $(`#total-${id}`).val(new_total)
        let html = (new_total * price).toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,')
        $(`#field-total-${id}`).html(`Rp. ${html}`)
        let prod_weight = parseInt($(`#field-price-${id}`).data('weight'))
        weight -= prod_weight * (new_total - 1)
        weight += prod_weight * new_total
        $('#inputWeight').val(weight)
    }

    const kemas = id => {
        $swal.fire({
                title: 'Yakin?',
                text: "Ingin mengemas order ini!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                cancelButtonText: 'Tidak',
                confirmButtonText: 'Ya!'
            })
            .then((result) => {
                if (result.isConfirmed) {
                    let url = `{{ route('transaction.set_status', ['id' => ':id', 'status' => ':status']) }}`
                    url = url.replace(':id', id)
                    url = url.replace(':status', DIKEMAS)
                    $axios.patch(`${url}`)
                        .then(({
                            data
                        }) => {
                            $swal.fire({
                                icon: 'success',
                                title: data.message.head,
                                text: data.message.body,
                            })
                            refresh_table(URL_NOW)
                        })
                }
            })
    }

    const tolak = id => {
        $swal.fire({
                title: 'Yakin?',
                text: "Ingin menolak order ini!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                cancelButtonText: 'Tidak',
                confirmButtonText: 'Ya!'
            })
            .then((result) => {
                if (result.isConfirmed) {
                    let url = `{{ route('transaction.set_status', ['id' => ':id', 'status' => ':status']) }}`
                    url = url.replace(':id', id)
                    url = url.replace(':status', DITOLAK)
                    $axios.patch(`${url}`)
                        .then(({
                            data
                        }) => {
                            $swal.fire({
                                icon: 'success',
                                title: data.message.head,
                                text: data.message.body,
                            })
                            refresh_table(URL_NOW)
                        })
                }
            })
    }

    const batalkan = id => {
        $swal.fire({
                title: 'Yakin?',
                text: "Ingin membatalkan order ini!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                cancelButtonText: 'Tidak',
                confirmButtonText: 'Ya!'
            })
            .then((result) => {
                if (result.isConfirmed) {
                    let url = `{{ route('transaction.set_status', ['id' => ':id', 'status' => ':status']) }}`
                    url = url.replace(':id', id)
                    url = url.replace(':status', BATAL)
                    $axios.patch(`${url}`)
                        .then(({
                            data
                        }) => {
                            $swal.fire({
                                icon: 'success',
                                title: data.message.head,
                                text: data.message.body,
                            })
                            refresh_table(URL_NOW)
                        })
                }
            })
    }

    const kirim = id => {
        $swal.fire({
                title: 'Yakin?',
                text: "Ingin mengirim order ini!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                cancelButtonText: 'Tidak',
                confirmButtonText: 'Ya!'
            })
            .then((result) => {
                if (result.isConfirmed) {
                    let url = `{{ route('transaction.set_status', ['id' => ':id', 'status' => ':status']) }}`
                    url = url.replace(':id', id)
                    url = url.replace(':status', DIKIRIM)
                    $axios.patch(`${url}`)
                        .then(({
                            data
                        }) => {
                            $swal.fire({
                                icon: 'success',
                                title: data.message.head,
                                text: data.message.body,
                            })
                            refresh_table(URL_NOW)
                        })
                }
            })
    }

    const barang_diterima = id => {
        $swal.fire({
                title: 'Yakin?',
                text: "Barang sudah diterima ini!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                cancelButtonText: 'Tidak',
                confirmButtonText: 'Ya!'
            })
            .then((result) => {
                if (result.isConfirmed) {
                    let url = `{{ route('transaction.set_status', ['id' => ':id', 'status' => ':status']) }}`
                    url = url.replace(':id', id)
                    url = url.replace(':status', DITERIMA)
                    $axios.patch(`${url}`)
                        .then(({
                            data
                        }) => {
                            $swal.fire({
                                icon: 'success',
                                title: data.message.head,
                                text: data.message.body,
                            })
                            refresh_table(URL_NOW)
                        })
                }
            })
    }

    const selesai = id => {
        $swal.fire({
                title: 'Yakin?',
                text: "Order telah selesai!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                cancelButtonText: 'Tidak',
                confirmButtonText: 'Ya!'
            })
            .then((result) => {
                if (result.isConfirmed) {
                    let url = `{{ route('transaction.set_status', ['id' => ':id', 'status' => ':status']) }}`
                    url = url.replace(':id', id)
                    url = url.replace(':status', SELESAI)
                    $axios.patch(`${url}`)
                        .then(({
                            data
                        }) => {
                            $swal.fire({
                                icon: 'success',
                                title: data.message.head,
                                text: data.message.body,
                            })
                            refresh_table(URL_NOW)
                        })
                }
            })
    }

    const set_resi = id => {
        new Promise((resolve, reject) => {
            let url = `{{ route('transaction.show_resi', ['id' => ':id']) }}`
            url = url.replace(':id', id)
            $axios.get(`${url}`)
                .then(({
                    data
                }) => {
                    $("#fieldCourierUpdate").val(data.data.shipping)
                    $("#fieldIdUpdateResi").val(data.data.id)
                    $("#fieldResiUpdate").val(data.data.waybill)
                    $("#modal-set-resi").modal('show')
                })
        })
    }

    const detail = id => {
        new Promise((resolve, reject) => {
            let url = `{{ route('transaction.show', ['transaction' => ':id']) }}`
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
                                    <img src="${url_asset}/${element.stock.product.image}" alt="gambar" class="img-fluid img-thumnail w-50" class="float-left">
                                    <p>${element.stock.product.name}</p>
                                    <p>${element.qty} Produk (${element.stock.product.weight}) x Rp ${element.price}</p>
                                </div>
                                <div class="col-md-6" style="border-left: 1px solid gray;">
                                    <p><strong>Harga Barang</strong></p>
                                    <p>Rp. ${total_harga}</p>
                                     <p><strong>Keterangan</strong></p>
                                <p>${element.note?element.note:'~'}</p>
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
                    let lacak = data['result']['manifest']
                    let html = ``
                    $.each(lacak, (index, element) => {
                        html += `<li>
                                    <a href="javascript:void(0)">${element['manifest_date']} | ${element['manifest_time']}</a>
                                    <p>${element['manifest_description']}</p>
                                </li>`
                    })
                    $("#fieldTimeline").html(html)
                    resolve(data)
                })
                .catch(err => {
                    reject(err)
                })
        })
    }
</script>
@endsection