@extends('layouts.dashboard')

@section('content')
<div class="section-body">
    <div class="card">
        <div class="card-header">
            <button class="btn btn-success" id="btnTambah"><i class="fas fa-plus"></i> Tambah Varian</button>
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
            @include('pages.master.variant.pagination')
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
                    <div class="form-group">
                        <label for="selectSubVarian">Varian Utama</label>
                        <select name="parent_id" id="selectSubVarian" class="form-control"></select>
                    </div>
                    <div class="form-group">
                        <label for="inputName">Nama Varian</label>
                        <input type="text" name="name" id="inputName" class="form-control" required>
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
<script>
    let type
    $(document).ready(function() {
        // Get All Varian
        refresh_get_variant()

        $("#btnTambah").on('click', () => {
            type = 'STORE'
            $("#modalTitle").html('Tambah Varian')
            $("#formTambah")[0].reset()
            $('#modal_tambah').modal('show')
        })

        $("#formTambah").on('submit', (e) => {
            e.preventDefault()
            let serializedData = $("#formTambah").serialize()

            if(type == "STORE") {
                new Promise((resolve, reject) => {
                    $axios.post(`{{ route('variant.store') }}`, serializedData)
                        .then(({data}) => {
                            $('#modal_tambah').modal('hide')
                            refresh_get_variant()
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
                let id_variant = $("#inputID").val()
                new Promise((resolve, reject) => {
                    $axios.put(`${URL_NOW}/${id_variant}`, serializedData)
                        .then(({data}) => {
                            $('#modal_tambah').modal('hide')
                            refresh_get_variant()
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
                    let variant = data.data
                    type = 'UPDATE'
                    $("#formTambah")[0].reset()
                    $("#inputID").val(variant.id)
                    $("#modalTitle").html('Update Varian')
                    if(variant.parent_id) $("#selectSubVarian").val(variant.parent_id)
                    $("#inputName").val(variant.name)
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

    const refresh_get_variant = () => {
        new Promise((resolve, reject) => {
            $axios.get(`{{ route('api.get_variant') }}`)
                .then(({data}) => {
                    let option = '<option value="">== Kosongkan Varian Utama==</option>'
                    $.each(data.data, (i, e) => {
                        if (e.id==e.parent_id) {
                            option += `<option value="${e.id}">${e.name}</option>`
                        }
                    })
                    $('#selectSubVarian').html(option)
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
</script>
@endsection
