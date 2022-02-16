@extends('layouts.dashboard')

@section('content')
<div class="section-body">
    <div class="card">
        <div class="card-header">
            <button class="btn btn-success" id="btnTambah"><i class="fas fa-plus"></i> Tambah Setting</button>
            <div class="ml-auto">
                <form action="" method="get" class="row">
                    <div class="input-group mb-3">
                        <input type="text" class="form-control" name="keyword" placeholder="Kata Kunci" value="{{ request()->keyword ?? '' }}">
                        <div class="input-group-append">
                            <button class="btn btn-primary ml-2"><i class="fas fa-search"></i>Cari</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <div class="card-body table-responsive overflow-auto" id="table_data">
            @include('pages.pengaturan.setting.pagination')
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
                <button type="button" onclick="resetForm()" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="" method="post" id="formTambah">
                @csrf
                <input type="hidden" name="id" id="inputID">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="">Key</label>
                        <select name="key" id="selectKey" class="form-control">
                            <option selected>==Pilih Key==</option>
                            <option value="minimal-belanja" title="atur minimal belanja user sesuai roe">minimal-belanja</option>
                            <option value="minimal-belanja-point">minimal-belanja-point</option>
                        </select>
                        <span id="keterangan" class="text-small"></span>
                    </div>
                    <div class="form-group d-none" id="selectRoleWrapper">
                        <label for="selectSubKategori">Role</label>
                        <select name="role" id="selectRole" class="form-control">
                            <option selected disabled>Pilih Role</option>
                            @foreach ($roles as $role)
                            <option value="{{$role->name}}">{{$role->name}}</option>
                            @endforeach
                        </select>
                        <div class="form-check mt-2 d-none">
                            <input class="form-check-input" type="radio" name="distributorType" id="exampleRadios1" value="old" checked>
                            <label class="form-check-label" for="exampleRadios1">
                                Distributor Lama(Terhitung 2020 ke bawah)
                            </label>
                        </div>
                        <div class="form-check d-none">
                            <input class="form-check-input" type="radio" name="distributorType" id="exampleRadios2" value="new">
                            <label class="form-check-label" for="exampleRadios2">
                                Distributor baru(Terhitung 2020 ke atas)
                            </label>
                        </div>
                    </div>
                    <div class="row" id="new_input">

                    </div>

                </div>
                <div class="modal-footer bg-whitesmoke br">
                    <button onclick="resetForm()" type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
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
        //modal input 
        $("#selectKey").on('change', () => {
            var value = $('#selectKey').val()
            if (value == "minimal-belanja") {
                $("#keterangan").html("Keterangan: Setting ini digunakan untuk mevalidasi apakah user berhak untuk mendapatkan diskon dan subsidi ongkir dan syarat untuk naik level.");
                $("#selectRoleWrapper").removeClass("d-none");
                $("#new_input").append($(`<div class="
                                col "> <div class="form-group" id=""> <label
                                for="">Minimal Transaksi / Bulan</label> <input type="text"
                                class="form-control"
                                name="value"
                                id="value" required>
                                </div></div>
                                <div class = "col"> <div class="form-group"
                                id="">
                                <label for="">Minimal Transaksi / Order </label> <input type="text"
                                class= "form-control"
                                name="minimal_transaction"
                                id="minimal_transaction"
                                required>
                                </div></div>
                                <div class="form-group"> 
                                <label
                                for="">Diskon(%)</label> <input type="text"
                                class="form-control"
                                name="discount"
                                id="discount" required>
                                <span class="">(jika user memenuhi minimal transaksi / perbulan)</span>
                                </div>
                                `));
            } else {
                $("#selectRoleWrapper").addClass("d-none");
                $("#new_input").children("div").remove();
                $("#keterangan").html("Keterangan: Setting ini digunakan untuk mevalidasi apakah user berhak untuk mendapatkan poin.");
            }
        });
        $("#selectRole").on("change", () => {
            var value = $('#selectRole').val()
            console.log(value)
            if (value == "distributor") {
                $(".form-check").removeClass('d-none');
            } else {
                $(".form-check").addClass('d-none');
            }
        })
        $("#btnTambah").on('click', () => {
            type = 'STORE'
            $("#modalTitle").html('Tambah Setting')
            $("#formTambah")[0].reset()
            $('#modal_tambah').modal('show')
        });
        $("input[type=radio][name=distributorType]").change(function() {
            console.log(this.value);
        })

        $("#formTambah").on('submit', (e) => {
            e.preventDefault()
            let serializedData = $("#formTambah").serialize();
$("#keterangan").html(null);
            if (type == "STORE") {
                new Promise((resolve, reject) => {
                    $axios.post(`{{ route('setting.store') }}`, serializedData)
                        .then(({
                            data
                        }) => {
                            $('#modal_tambah').modal('hide');
                            $('#selectKey').val("");
                            $("#new_input").children("div").remove();
                            refresh_table(URL_NOW);
                            $swal.fire({
                                icon: 'success',
                                title: data.message.head,
                                text: data.message.body
                            });
                        })
                        .catch(err => {
                            console.log(err);
                            throwErr(err)
                        }).then(() => {
                            $("#modal_tambah").modal("hide");
                            $('#selectKey').val("");
                            $("#new_input").children("div").remove();
                        });
                });
            } else if (type == "UPDATE") {
                let id_setting = $("#inputID").val();
                console.log("oke you are going to update");
                // return;
                new Promise((resolve, reject) => {
                    $axios.put(`${URL_NOW}/${id_setting}`, serializedData)
                        .then(({
                            data
                        }) => {
                            $('#modal_tambah').modal('hide');
                            $('#selectKey').val("");
                            $("#new_input").children("div").remove();
                            refresh_table(URL_NOW);
                            $swal.fire({
                                icon: 'success',
                                title: data.message.head,
                                text: data.message.body
                            });
                        })
                        .catch(err => {
                            throwErr(err);
                            $('#selectKey').val("");
                            $("#new_input").children("div").remove();
                        });
                });
            }
        })
    })

    const editData = id => {
        new Promise((resolve, reject) => {
            $axios.get(`${URL_NOW}/${id}`)
                .then(({
                    data
                }) => {
                    let setting = data.data;
                    console.log(setting);
                    type = 'UPDATE';
                    $("#formTambah")[0].reset();
                    $("#inputID").val(setting.id);
                    $("#key").val(setting.key);
                    $("#modalTitle").html('Edit Setting')
                    $("#value").val(setting.value);
                    $('#modal_tambah').modal('show');
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

    const resetForm = () => {
        $('#selectKey').val("");
        $("#new_input").children("div").remove();
    }

    const deleteData = id => {
        console.log(id);
        // return;
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
</script>
@endsection