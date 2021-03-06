@extends('layouts.dashboard')

@section('content')
<div class="section-body">
    <div class="card">
        <div class="card-header">
            <button class="btn btn-success" id="btnTambah"><i class="fas fa-plus"></i> Tambah Diskon</button>
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
            @include('pages.master.discount.pagination')
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
                        <label for="inputName">Nama</label>
                        <input type="text" name="name" id="inputName" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="inputWight">Diskon</label>
                        <input type="text" name="discount" id="inputDiscount" class="form-control" required>
                    </div>
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
        $("#btnTambah").on('click', () => {
            type = 'STORE'
            $("#modalTitle").html('Tambah Diskon')
            $("#formTambah")[0].reset()
            $('#modal_tambah').modal('show')
        })

        $('#inputPrice').autoNumeric('init')

        $("#formTambah").on('submit', (e) => {
            e.preventDefault()
            if(type == "STORE") {
                new Promise((resolve, reject) => {
                    $axios.post(`{{ route('discount.store') }}`, $("#formTambah").serialize())
                        .then(({data}) => {
                            $('#modal_tambah').modal('hide')
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
                let id_discount = $("#inputID").val()
                new Promise((resolve, reject) => {
                    $axios.put(`${URL_NOW}/${id_discount}`, $("#formTambah").serialize())
                        .then(({data}) => {
                            $('#modal_tambah').modal('hide')
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

    const editData = id => {
        new Promise((resolve, reject) => {
            $axios.get(`${URL_NOW}/${id}`)
                .then(({data}) => {
                    let dc = data.data
                    type = 'UPDATE'
                    $("#formTambah")[0].reset()
                    $("#inputID").val(dc.id)
                    $("#modalTitle").html('Update Diskon')
                    $("#inputName").val(dc.name)
                    $("#inputDiscount").val(dc.discount)
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

    const setStatusDiscount = (id, status) => {
        let teks = ``
        if(status) {
            teks = `Ingin mengaktifkan diskon ini`
        } else {
            teks = `Ingin menonaktifkan diskon ini`
        }
        $swal.fire({
            title: 'Yakin?',
            text: teks,
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
                    $axios.post(`{{ route('discount.set_status') }}`, {id: id, status: status})
                        .then(({data}) => {
                            toastr.success(data.message.head, data.message.body)
                            refresh_table(URL_NOW)
                        })
                })
            }
        })
    }
    </script>
@endsection
