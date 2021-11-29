@extends('layouts.dashboard')

@section('content')
<div class="section-body">
    <div class="card">
        <div class="card-header">
            <button class="btn btn-success" id="btnTambah"><i class="fas fa-plus"></i> Tambah User</button>
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
            @include('pages.pengaturan.user.pagination')
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
                @unlessrole('superadmin')
                <input type="hidden" name="upper" value="{{ $user->id }}">
                @endrole
                <input type="hidden" name="id" id="inputID">
                <div class="modal-body">
                    {{-- @role('superadmin') --}}
                    <div class="form-group">
                        <label for="selectRole">Role</label>
                        <select name="role" id="selectRole" class="form-control" required></select>
                    </div>
                    {{-- @endrole --}}
                    <div class="form-group">
                        <label for="inputName">Nama</label>
                        <input type="text" name="name" id="inputName" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="inputEmail">Email</label>
                        <input type="email" name="email" id="inputEmail" class="form-control" required>
                    </div>
                    @role('superadmin')
                    <div class="form-group">
                        <label for="inputUpper">Atasan</label>
                        <select name="upper" id="inputUpper" class="form-control" autocomplete="off">
                            @foreach ($all_user as $value)
                                <option value="{{ $value->id }}">{{ $value->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    @endrole
                    <p class="text-danger text-center" id="teksPassword" style="display: none">Kosongkan jika tidak merubah password</p>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="inputPassword">Password</label>
                                <input type="password" name="password" id="inputPassword" class="form-control" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="inputPasswordConfirmation">Konfirmasi Password</label>
                                <input type="password" name="password_confirmation" id="inputPasswordConfirmation" class="form-control" required>
                            </div>
                        </div>
                    </div>
                    <div class="row" id="form-tambahan-member">
                        <div class="form-group col-6">
                            <label for="ttl">Tempat Tanggal Lahir</label>
                            <input id="ttl" type="date" class="form-control @error('ttl') is-invalid @enderror" name="ttl" value="{{ old('ttl') }}">
                            @error('ttl')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="form-group col-6">
                            <label for="nowhatsapp">No. WhatsApp</label>
                            <input id="nowhatsapp" type="number" class="form-control @error('nowhatsapp') is-invalid @enderror" name="nowhatsapp" value="{{ old('nowhatsapp') }}" placeholder="08123456789">
                            @error('nowhatsapp')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="form-group col-6">
                            <label for="instagram">Instagram</label>
                            <input id="instagram" type="text" class="form-control @error('instagram') is-invalid @enderror" name="instagram" value="{{ old('instagram') }}" placeholder="'@' username">
                            @error('instagram')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="form-group col-6">
                            <label for="facebook">Facebook</label>
                            <input id="facebook" type="text" class="form-control @error('facebook') is-invalid @enderror" name="facebook" value="{{ old('facebook') }}" placeholder="username">
                            @error('facebook')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="form-group col-6">
                            <label for="marketplace">Marketplace</label>
                            <input id="marketplace" type="text" class="form-control @error('marketplace') is-invalid @enderror" name="marketplace" value="{{ old('marketplace') }}" placeholder="Shopee, Tokopedia, dll">
                            @error('marketplace')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="form-group col-6">
                            <label for="mou">File MOU <span class="text-success" id="filedownload"></span></label>
                            <input id="mou" type="file" class="form-control @error('mou') is-invalid @enderror" name="mou" value="{{ old('mou') }}">
                            @error('mou')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
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
<script src="{{ asset('assets-dashboard/js/page/bootstrap-modal.js') }}"></script>
<script>
    let type
    $(document).ready(function() {
        // Get All ROle
        new Promise((resolve, reject) => {
            $axios.get(`{{ route('api.get_role') }}`)
                .then(({data}) => {
                    let option = '<option value="">== Pilih Role ==</option>'
                    $.each(data.data, (i, e) => {
                        option += `<option value="${e.id}">${e.name}</option>`
                    })
                    $('#selectRole').html(option)
                })
                .catch(err => {
                    $swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Something went wrong!',
                    })
                })
        })

        $("#btnTambah").on('click', () => {
            type = 'STORE'
            $("#modalTitle").html('Tambah User')
            $("#teksPassword").hide()
            $("#inputPassword").prop('required', true)
            $("#inputPasswordConfirmation").prop('required', true)
            $("#formTambah")[0].reset()
            $('#modal_tambah').modal('show')
        })

        $("#formTambah").on('submit', (e) => {
            e.preventDefault()
            let serializedData = $("#formTambah").serialize()

            if(type == "STORE") {
                new Promise((resolve, reject) => {
                    $axios.post(`{{ route('users.store') }}`, serializedData)
                        .then(({data}) => {
                            $('#modal_tambah').modal('hide')
                            refresh_table(URL_NOW)
                            toastr.success(data.message.head, data.message.body)
                        })
                        .catch(err => {
                            throwErr(err)
                        })
                })
            } else if(type == "UPDATE") {
                let id_user = $("#inputID").val()
                new Promise((resolve, reject) => {
                    $axios.put(`${URL_NOW}/${id_user}`, serializedData)
                        .then(({data}) => {
                            $('#modal_tambah').modal('hide')
                            refresh_table(URL_NOW)
                            toastr.success(data.message.head, data.message.body)
                        })
                        .catch(err => {
                            throwErr(err)
                        })
                })
            }
        })
    })

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
                            toastr.success(data.message.head, data.message.body)
                            refresh_table(URL_NOW)
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
        })
    }

    const editData = id => {
        new Promise((resolve, reject) => {
            $axios.get(`${URL_NOW}/${id}`)
                .then(({data}) => {
                    let user = data.data
                    type = 'UPDATE'
                    $("#formTambah")[0].reset()
                    $("#inputID").val(user.id)
                    $("#modalTitle").html('Update User')
                    $("#selectRole").val(user.roles[0].id)
                    $("#inputName").val(user.name)
                    $("#inputEmail").val(user.email)
                    $("#inputUpper").val(user.upper)
                    $("#teksPassword").show()
                    $("#inputPassword").prop('required', false)
                    $("#inputPasswordConfirmation").prop('required', false)
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

    const setStatusUser = (id, status) => {
        let teks = ``
        if(status) {
            teks = `Ingin mengaktifkan user ini`
        } else {
            teks = `Ingin menonaktifkan user ini`
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
                    $axios.post(`{{ route('users.set_status') }}`, {id: id, status: status})
                        .then(({data}) => {
                            toastr.success(data.message.head, data.message.body)
                            refresh_table(URL_NOW)
                        })
                })
            }
        })
    }

    const upgradeUser = id => {
        $swal.fire({
            title: 'Yakin?',
            text: "Ingin mengupgrade user ini",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            cancelButtonText: 'Tidak',
            confirmButtonText: 'Ya!'
        })
        .then(result => {
            if(result.isConfirmed) {
                new Promise((resolve ,reject) => {
                    $axios.post(`{{ route('user.upgrade') }}`, {id: id})
                        .then(({data}) => {
                            toastr.success(data.message.head, data.message.body)
                            refresh_table(URL_NOW)
                        })
                })
            }
        })
    }
    $(document).ready(function(){
        $('#form-tambahan-member').hide();
        $('#col-referral').hide();
    })
    $('#selectRole').on('change', function(){
            console.log($(this).val())
            if ($(this).val() == 2 || $(this).val() == 3 || $(this).val() == 4 || $(this).val() == 5) {
                $('#col-referral').show();
                $('#form-tambahan-member').show();
                $('#filedownload').html(`<a href="{{asset('core/mou/${$(this).val()}.docx')}}">download template</a>`)
            }else{
                $('#col-referral').hide();
                $('#form-tambahan-member').hide();
            }
        })
</script>
@endsection
