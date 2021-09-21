@extends('layouts.dashboard')

@section('content')
<div class="section-body">
    <div class="card">
        <div class="card-header">
            @role('reseller')
            <button class="btn btn-warning" id="btnOut" onclick="add()"><i class="fas fa-plus"></i> Keluarkan Produk</button>
            @endrole
            <button class="btn btn-outline-primary ml-2" onclick="refresh_table(URL_NOW)"><i class="fas fa-sync"></i>Refresh</button>
            <div class="ml-auto">
                <form action="" method="get" class="row">
                    <div class="input-group mb-3">
                        <input type="text" class="form-control" name="keyword" placeholder="Kata Kunci"
                            value="{{ request()->keyword ?? '' }}">
                        <div class="input-group-append">
                            <button class="btn btn-primary"><i class="fas fa-search"></i>Cari</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <div class="card-body table-responsive" id="table_data">
            @include('pages.order.stock.pagination')
        </div>
    </div>
</div>
@endsection

@section('modal')
@role('reseller')
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
                <input type="hidden" name="status" value="2">
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
                                @forelse ($stock_reseller as $key => $value)
                                <input type="hidden" name="id[]" value="{{ $value->product_id }}">
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td><img src="{{ asset('upload/product').'/'.$value->product->image }}" alt="{{ $value->product->image }}" class="img-fluid" width="200"></td>
                                    <td>{{ $value->product->name }}</td>
                                    <td id="field-price-{{ $value->product_id }}" data-weight="{{ $value->product->weight }}" data-price="{{ $value->product->price }}">Rp. {{ number_format($value->product->price) }}</td>
                                    <td>
                                        <div class="row">
                                            <div class="col-3">
                                                <button type="button" class="btn btn-sm btn-primary" onclick="minus({{ $value->product_id }})"><i class="fas fa-minus"></i></button>
                                            </div>
                                            <div class="col-6">
                                                <input name="qty[]" type="text" data-max="{{ $value->stock }}" id="total-{{ $value->product_id }}" class="form-control qty text-center" value="0" min="0" readonly>
                                            </div>
                                            <div class="col-3">
                                                <button type="button" class="btn btn-sm btn-primary" onclick="plus({{ $value->product_id }})"><i class="fas fa-plus"></i></button>
                                            </div>
                                        </div>
                                    </td>
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
                            <div class="form-group col-12">
                                <label for="">Provinsi</label>
                                <select name="province_id" id="province_id" class="form-control">
                                    <option value="" selected disabled>== Pilih Povinsi ==</option>
                                </select>
                            </div>
                            <div class="form-group col-12">
                                <label for="">Kabupaten/Kota</label>
                                <select name="city_id" id="city_id" class="form-control">
                                    <option value="" selected disabled>== Pilih Kabupaten/Kota ==</option>
                                </select>
                            </div>
                            <div class="form-group col-12">
                                <label for="">Kecamatan</label>
                                <select name="subdistrict_id" id="subdistricts_id" class="form-control">
                                    <option value="" selected disabled>== Pilih Kecamatan ==</option>
                                </select>
                            </div>

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
@endrole

<div class="modal fade" tabindex="-1" role="dialog" id="modal_edit">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalTitleEdit"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="" method="post" id="formEdit">
                @csrf
                <input type="hidden" name="id" id="inputIDUpdate">
                <div class="modal-body">
                    <table class="table table-hovered table-bordered">
                        <tr>
                            <td>Nama Produk</td>
                            <td> : </td>
                            <td id="fieldEditProductName"></td>
                        </tr>
                        <tr>
                            <td>Stok</td>
                            <td> : </td>
                            <td id="fieldEditStockProduct"></td>
                        </tr>
                        <tr>
                            <td>Harga Minimal</td>
                            <td> : </td>
                            <td id="fieldEditMinimalPrice"></td>
                        </tr>
                        <tr>
                            <td>Harga Produk</td>
                            <td> : </td>
                            <td id="fieldEditProductPrice"></td>
                        </tr>
                    </table>
                    <div class="form-group">
                        <label for="">Harga Jual</label>
                        <input type="text" class="form-control" id="fieldEditPrice" name="member_price">
                    </div>
                    <div class="form-group">
                        <label for="">Diskon <small class="text-danger">Dalam Persen</small></label>
                        <input type="text" class="form-control" id="fieldDiscount" name="discount">
                    </div>
                </div>
                <div class="modal-footer bg-whitesmoke br">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary" id="btn-edit">Simpan</button>
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
        ul.timeline > li {
            margin: 20px 0;
            padding-left: 20px;
        }
        ul.timeline > li:before {
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
    const NONAKTIFKAN = 0
    const DIAKTIFKAN = 1

    $(document).ready(() => {
        @role('reseller')
        $(".qty").keydown(function(e){
            if(((e.keyCode < 48) || (e.keyCode > 57))&& e.keyCode != 8){
                e.preventDefault()
            }
        })

        $("#courier").select2({
            dropdownParent: $("#modal_tambah")
        })

        $("#btn-simpan").attr('disabled', 'disabled')

        new Promise((resolve, reject) => {
            $axios.post(`{{ route('api.check_home') }}`, {api_token:`{{ auth()->user()->api_token }}`})
                .catch(err => {
                    $swal.fire({
                        icon: 'error',
                        title: err.response.data.message.head,
                        text: err.response.data.message.body,
                    })
                    $("#btnOut").attr('disabled', 'disabled')
                })
        })

        new Promise((resolve, reject) => {
            $axios.get(`{{ route('api.get_courier') }}`)
                .then(({data}) => {
                    let html = `<option selected disabled value="">== Pilih Kurir ==</option>`
                    $.each(data, (i, e) => {
                        html += `<option value="${e}">${e}</option>`
                    })
                    $("#courier").html(html)
                })
        })

        $("#btn-courier").on('click', () => {
            let local_weight = $("#inputWeight").val()
            if(local_weight <= 0) {
                return $swal.fire({
                    icon: 'error',
                    title: "Gagal",
                    text: "Anda belum memilih produk"
                })
            }

            let courier = $("#courier").val()
            if(!courier) {
                return $swal.fire({
                    icon: 'error',
                    title: "Gagal",
                    text: "Anda belum memilih kurir"
                })
            }
            let subdistrict = $('#subdistricts_id').val()
            if(!subdistrict) {
                return $swal.fire({
                    icon: 'error',
                    title: "Gagal",
                    text: "Anda belum memilih alamat tujuan"
                })
            }

            loading('show', $("#btn-courier"))
            new Promise((resolve, reject) => {
                $axios.post(`{{ route('api.get_ongkir') }}`, {
                    'api_token': `{{ auth()->user()->api_token }}`,
                    'origin': `{{ $upper_origin['subdistrict'] ?? '' }}`,
                    'destination': $('#subdistricts_id').val(),
                    'weight': local_weight,
                    'courier': courier
                })
                .then(({data}) => {
                    if(data.status.code == 200) {
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

        $('#province_id').select2({
            dropdownParent: $("#modal_tambah")
        })
        $('#city_id').select2({
            dropdownParent: $("#modal_tambah")
        })
        $('#subdistricts_id').select2({
            dropdownParent: $("#modal_tambah")
        })

        get_province()

        $("#province_id").on('change', () => {
            let province_id = $("#province_id").val()
            get_city(province_id)
        })

        $("#city_id").on('change', () => {
            let city_id = $("#city_id").val()
            get_subdistrict(city_id)
        })

        $("#formTambah").on('submit', (e) => {
            e.preventDefault()
            let serializedData = $("#formTambah").serialize()
            if(!$('input[name="cost"]:checked').val()) {
                return $swal.fire({
                    icon: 'error',
                    title: "Gagal",
                    text: "Anda belum memilih jenis ongkos kirim"
                })
            }
            new Promise((resolve, reject) => {
                $axios.post(`{{ route('order.store') }}`, serializedData)
                    .then(({data}) => {
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
        @endrole

        $("#fieldEditPrice,#fieldDiscount").inputFilter(value => {
            return /^\d*$/.test(value)
        })

        $("#formEdit").on('submit', e => {
            e.preventDefault()
            let url = `{{ route('stock.update', ['id' => ':id']) }}`
            url = url.replace(':id', $("#inputIDUpdate").val())
            new Promise((resolve, reject) => {
                $axios.put(`${url}`, $("#formEdit").serialize())
                    .then(res => {
                        let data = res.data
                        refresh_table(URL_NOW)
                        $swal.fire({
                            icon: 'success',
                            title: data.message.head,
                            text: data.message.body
                        })
                        $('#modal_edit').modal('hide')
                    })
                    .catch(err => {
                        throwErr(err)
                    })
            })
        })
    })

    const aktifkan = id => {
        $swal.fire({
            title: 'Yakin?',
            text: "Ingin mengkatifkan produk ini!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            cancelButtonText: 'Tidak',
            confirmButtonText: 'Ya!'
        })
        .then((result) => {
            if (result.isConfirmed) {
                let url = `{{ route('stock.set_status', ['id' => ':id', 'status' => ':status']) }}`
                url = url.replace(':id', id)
                url = url.replace(':status', DIAKTIFKAN)
                loading('show', `.press-btn-${id}`)
                $axios.patch(`${url}`)
                    .then(({data}) => {
                        toastr.success(data.message.head, data.message.body)
                        loading('hide', `.press-btn-${id}`)
                        refresh_table(URL_NOW)
                    })
            }
        })
    }

    const nonaktifkan = id => {
        $swal.fire({
            title: 'Yakin?',
            text: "Ingin menonaktifkan produk ini!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            cancelButtonText: 'Tidak',
            confirmButtonText: 'Ya!'
        })
        .then((result) => {
            if (result.isConfirmed) {
                let url = `{{ route('stock.set_status', ['id' => ':id', 'status' => ':status']) }}`
                url = url.replace(':id', id)
                url = url.replace(':status', NONAKTIFKAN)
                loading('show', `.press-btn-${id}`)
                $axios.patch(`${url}`)
                    .then(({data}) => {
                        toastr.success(data.message.head, data.message.body)
                        loading('hide', `.press-btn-${id}`)
                        refresh_table(URL_NOW)
                    })
            }
        })
    }

    const discount = (id, status) => {
        let text
        if(status) {
            text = "mengaktifkan"
        } else {
            text = "menonaktifkan"
        }
        $swal.fire({
            title: 'Yakin?',
            text: `Ingin ${text} diskon ini!`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            cancelButtonText: 'Tidak',
            confirmButtonText: 'Ya!'
        })
        .then((result) => {
            if (result.isConfirmed) {
                let url = `{{ route('stock.set_status_discount', ['id' => ':id', 'status' => ':status']) }}`
                url = url.replace(':id', id)
                url = url.replace(':status', status)
                loading('show', `.discount-btn-${id}`)
                $axios.patch(`${url}`)
                    .then(({data}) => {
                        toastr.success(data.message.head, data.message.body)
                        loading('hide', `.discount-btn-${id}`)
                        refresh_table(URL_NOW)
                    })
            }
        })
    }

    const edit = id => {
        new Promise((resolve, reject) => {
            let url = `{{ route('stock.edit', ":id") }}`
            url = url.replace(":id", id)
            $axios.get(`${url}`)
                .then(({data}) => {
                    let result = data.data
                    let price = 0
                    $('#modalTitleEdit').html('Edit')
                    $('#modal_edit').modal('show')
                    $("#inputIDUpdate").val(result.id)
                    $("#fieldEditProductName").html(result.product.name)
                    $("#fieldEditStockProduct").html(result.stock)
                    $("#fieldEditMinimalPrice").html(result.product.price)
                    if(result.member_price) {
                        price = result.member_price
                    } else {
                        price = result.product.price
                    }
                    $("#fieldEditProductPrice").html(price)
                    $("#fieldEditPrice").val(price)
                    let discout = 0
                    if(result.discount.discount) {
                        discout = result.discount.discount
                    }
                    $("#fieldDiscount").val(discout)
                })
                .catch(err => {
                    console.log(err)
                })
        })
    }

    const add = () => {
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
    }
    @role('reseller')
    const minus = id => {
        let total = parseInt($(`#total-${id}`).val())
        let price = parseInt($(`#field-price-${id}`).data('price'))
        let prod_weight = parseInt($(`#field-price-${id}`).data('weight'))
        if(total > 0) {
            let new_total = total - 1
            $(`#total-${id}`).val(new_total)
            let html = (new_total*price).toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,')
            $(`#field-total-${id}`).html(`Rp. ${html}`)
            let prod_weight = parseInt($(`#field-price-${id}`).data('weight'))
            weight -= prod_weight
            $('#inputWeight').val(weight)
        }
    }

    const plus = id => {
        let max = $(`#total-${id}`).data('max')
        let total = parseInt($(`#total-${id}`).val())
        let price = parseInt($(`#field-price-${id}`).data('price'))
        let new_total = total + 1
        if(new_total > max) {
            return $swal.fire({
                        icon: 'error',
                        title: 'Gagal',
                        text: 'Stok kurang dari '+max
                    })
        }
        $(`#total-${id}`).val(new_total)
        let html = (new_total*price).toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,')
        $(`#field-total-${id}`).html(`Rp. ${html}`)
        let prod_weight = parseInt($(`#field-price-${id}`).data('weight'))
        weight -= prod_weight * (new_total - 1)
        weight += prod_weight * new_total
        $('#inputWeight').val(weight)
    }

    const get_province = (id = null) => {
        return new Promise((resolve, reject) => {
            $axios.get(`{{ route('api.get_provice') }}`)
                .then(({data}) => {
                    let result = data.data
                    $("#province_id").empty()
                    let html = `<option value="" selected disabled>== Pilih Povinsi ==</option>`
                    $.each(result, (id_elemnt, el) => {
                        if(el.province_id == id) {
                            html += `<option value="${el.province_id}" selected>${el.name}</option>`
                        } else {
                            html += `<option value="${el.province_id}">${el.name}</option>`
                        }
                    })
                    $("#city_id").html(`<option value="" selected disabled>== Pilih Kabupaten/Kota ==</option>`)
                    $("#subdistricts_id").html(`<option value="" selected disabled>== Pilih Kecamatan ==</option>`)
                    $("#province_id").html(html)
                    resolve(result)
                })
                .catch(err => {
                    console.log(err)
                    reject(err)
                })
        })
    }

    const get_city = (id, id_city = null) => {
        return new Promise((resolve, reject) => {
            $axios.get(`{{ route('api.get_city') }}/${id}`)
                .then(({data}) => {
                    let result = data.data
                    $("#city_id").empty()
                    let html = `<option value="" selected disabled>== Pilih Kota ==</option>`
                    $.each(result, (id, el) => {
                        if(el.city_id == id_city) {
                            html += `<option value="${el.city_id}" selected>${el.name} - ${el.type}</option>`
                        } else {
                            html += `<option value="${el.city_id}">${el.name} - ${el.type}</option>`
                        }
                    })
                    $("#subdistricts_id").html(`<option value="" selected disabled>== Pilih Kecamatan ==</option>`)
                    $("#city_id").html(html)
                    resolve(result)
                })
                .catch(err => {
                    console.log(err)
                    reject(err)
                })
        })
    }

    const get_subdistrict = (id, id_subdistrict_old) => {
        return new Promise((resolve, reject) => {
            $axios.get(`{{ route('api.get_subdistict') }}/${id}`)
                .then(({data}) => {
                    let result = data.data
                    $("#subdistricts_id").empty()
                    let html = `<option value="" selected disabled>== Pilih Kecamatan ==</option>`
                    $.each(result, (id, el) => {
                        if(el.subdistrict_id == id_subdistrict_old) {
                            html += `<option value="${el.subdistrict_id}" selected>${el.subdistrict_name}</option>`
                        } else {
                            html += `<option value="${el.subdistrict_id}">${el.subdistrict_name}</option>`
                        }
                    })
                    $("#subdistricts_id").html(html)
                    resolve(result)
                })
                .catch(err => {
                    console.log(err)
                    reject(err)
                })
        })
    }
    @endrole
</script>
@endsection
