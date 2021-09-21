@extends('layouts.dashboard')

@section('content')
<div class="section-body">
    <div class="card">
        <div class="card-header">
            <button class="btn btn-success" id="btnTambah"><i class="fas fa-plus"></i> Tambah Product</button>
            <div class="ml-auto">
                <form action="" method="get" class="row">
                    <div class="input-group mb-3">
                        <input type="text" class="form-control" name="keyword" placeholder="Kata Kunci"
                            value="{{ request()->keyword ?? '' }}">
                        <div class="input-group-append">
                            <button class="btn btn-primary ml-2"><i class="fas fa-search"></i>Cari</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <div class="card-body table-responsive" id="table_data">
            @include('pages.master.product.pagination')
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
            <form action="" method="post" id="formTambah" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="id" id="inputID">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="selectKategori">Kategori</label>
                        <select name="category_id" id="selectKategori" class="form-control" required></select>
                    </div>
                    <div class="form-group">
                        <label for="inputName">Nama</label>
                        <input type="text" name="name" id="inputName" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="inputPrice">Harga</label>
                        <input type="text" name="price" id="inputPrice" class="form-control" data-a-sign="Rp. " data-a-dec="," data-a-sep="." required>
                    </div>
                    <div class="form-group">
                        <label for="inputWight">Berat</label>
                        <input type="text" name="weight" id="inputWight" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="inputDescription">Deskripsi</label>
                        <textarea name="description" id="inputDescription" cols="30" rows="5" class="form-control"></textarea>
                    </div>
                    <p class="text-danger text-center" id="teksImage" style="display: none">Jangan upload gambar jika tidak ingin mengubahnya</p>
                    <div class="form-group">
                        <label for="inputImage">Foto Produk</label>
                        <input type="file" name="image" id="inputImage" class="form-control" required>
                    </div>
                    <div id="fieldFoto" style="display: none"></div>
                </div>
                <div class="modal-footer bg-whitesmoke br">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('js')
<script src="{{ asset('vendor/autoNumeric.js') }}"></script>
    <script>
    let type
    $(document).ready(function() {
        refresh_get_category()
        $("#btnTambah").on('click', () => {
            type = 'STORE'
            $("#modalTitle").html('Tambah Produk')
            $("#formTambah")[0].reset()
            $("#fieldFoto").hide()
            $("#teksImage").hide()
            $("#inputImage").prop('required', true)
            $('#modal_tambah').modal('show')
        })

        $('#inputPrice').autoNumeric('init')

        $("#formTambah").on('submit', (e) => {
            e.preventDefault()
            let FormDataVar = new FormData($("#formTambah")[0])
            let image = $("#inputImage")[0].files
            FormDataVar.append('image', image[0])

            if(type == "STORE") {
                new Promise((resolve, reject) => {
                    $axios.post(`{{ route('product.store') }}`, FormDataVar, {
                        headers: {
                            'Content-type': 'multipart/form-data'
                        }
                    })
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
            } else if(type == "UPDATE") {
                let id_product = $("#inputID").val()
                FormDataVar.append('_method', 'PUT')
                new Promise((resolve, reject) => {
                    $axios.post(`${URL_NOW}/${id_product}`, FormDataVar, {
                        headers: {
                            'Content-type': 'multipart/form-data'
                        }
                    })
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
            }
        })
    })

    const refresh_get_category = () => {
        new Promise((resolve, reject) => {
            $axios.get(`{{ route('api.get_category') }}`)
                .then(({data}) => {
                    let option = '<option value="" disabled selected>== Pilih Kategori ==</option>'
                    $.each(data.data, (i, e) => {
                        option += `<option value="${e.id}">${e.name}</option>`
                    })
                    $('#selectKategori').html(option)
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

    const editData = id => {
        new Promise((resolve, reject) => {
            $axios.get(`${URL_NOW}/${id}`)
                .then(({data}) => {
                    let product = data.data
                    type = 'UPDATE'
                    $("#formTambah")[0].reset()
                    $("#inputID").val(product.id)
                    $("#modalTitle").html('Update Produk')
                    $('#fieldFoto').html(`<img src="${BASE_URL}/upload/product/${product.image}" alt="${BASE_URL}/upload/product/${product.image}" class="img-fluid" width="300">`)
                    $('#fieldFoto').show()
                    $("#teksImage").show()
                    $("#selectKategori").val(product.category_id)
                    $("#inputName").val(product.name)
                    $("#inputPrice").val(product.price)
                    $("#inputWight").val(product.weight)
                    $("#inputDescription").val(product.description)
                    $("#inputImage").prop('required', false)
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
                            .then(({data}) => {
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
    </script>
@endsection
