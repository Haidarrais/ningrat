@extends('layouts.dashboard')

@section('content')
<div class="section-body">
    <div class="card">
        <div class="card-header">
            <button class="btn btn-success" id="btnTambah"><i class="fas fa-plus"></i> Tambah Product</button>
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
                    <!-- <div class="form-group">
                        <label for="inputImage">Foto Produk</label>
                        <input type="file" name="image" id="inputImage" class="form-control" required>
                    </div> -->
                    <div class="input-group control-group lst increment">
                        <input type="file" name="image[]" id="inputImage" class="myfrm form-control inputImage">
                        <div class="input-group-btn">
                            <button class="btn btn-success btn-success-images" type="button"><i class="fldemo glyphicon glyphicon-plus"></i>Add</button>
                        </div>
                    </div>
                    <div class="clone hide" hidden>
                        <div class="hdtuto control-group lst input-group" style="margin-top:10px">
                            <input type="file" name="image[]" class="myfrm form-control inputImage">
                            <div class="input-group-btn">
                                <button class="btn btn-danger btn-danger-images" type="button"><i class="fldemo glyphicon glyphicon-remove"></i> Remove</button>
                            </div>
                        </div>
                    </div>
                    <div id="fieldFoto" style="display: none" class="d-flex flex-wrap justify-content-around flex-grow-1"></div>
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
    let countimageinput = 1;
    var items = $(".hdtuto").length;
    $(document).ready(function() {
        refresh_get_category()
        $("#btnTambah").on('click', (e) => {
            let a = $(".btn-danger-images");
            if (a) {
                for (let index = 0; index < a.length; index++) {
                    const element =
                        a[index];
                    if (element.parentElement.closest('.cloned')) {
                        element.parentElement.closest('.cloned').remove();
                    }
                }
            }
            e.preventDefault();
            type = 'STORE';
            countimageinput = 1;
            $("#modalTitle").html('Tambah Produk')
            $("#formTambah")[0].reset()
            $("#fieldFoto").hide()
            $("#fieldFoto").removeClass('d-flex');
            $("#teksImage").hide()
            // $(".inputImage").prop('required', true)
            $('#modal_tambah').modal('show')
        })

        $('#inputPrice').autoNumeric('init')

        $("#formTambah").on('submit', (e) => {
            e.preventDefault();
            let FormDataVar = new FormData($("#formTambah")[0]);
            let image = $(".inputImage");
            let tempName = "";
            // console.log(image.length);
            // for (let index = 0; index < image.length; index++) {
            //     if (image[index].files[0]) {
            //         // console.log(image[index].files[0].name != tempName);
            //         if (image[index].files[0].name != tempName) {
            //             FormDataVar.append('image[]', image[index].files[0]);
            //             if (image[index].files[0].name !== "") {
            //                 tempName = image[index].files[0].name;
            //             }
            //         } else {

            //         }
            //     }
            // }
            // for (var pair of FormDataVar.entries()) {
            //     console.log(pair[1]);
            // }
            // return;

            // console.log(FormDataVar);
            if (type == "STORE") {
                new Promise((resolve, reject) => {
                    $axios.post(`{{ route('product.store') }}`, FormDataVar, {
                            headers: {
                                'Content-type': 'multipart/form-data'
                            }
                        })
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
            } else if (type == "UPDATE") {
                let id_product = $("#inputID").val()
                FormDataVar.append('_method', 'PUT')
                new Promise((resolve, reject) => {
                    $axios.post(`${URL_NOW}/${id_product}`, FormDataVar, {
                            headers: {
                                'Content-type': 'multipart/form-data'
                            }
                        })
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
            }
        });
        $(".btn-success-images").click(function() {
            if (countimageinput >= 5) {
                $swal.fire({
                    icon: 'warning',
                    title: "Foto",
                    text: "Hanya bisa upload maksimal 5 foto"
                });
                return;
            }
            var lsthmtl = $(".clone").children().clone().addClass('cloned');
            $(".increment").after(lsthmtl);
            countimageinput += 1;
        });

        $("body").on("click", ".btn-danger-images", function() {
            $(this).parents(".hdtuto").remove();
            countimageinput -= 1;
        });


    });

    const refresh_get_category = () => {
        new Promise((resolve, reject) => {
            $axios.get(`{{ route('api.get_category') }}`)
                .then(({
                    data
                }) => {
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
        let a = $(".btn-danger-images");
        if (a) {
            for (let index = 0; index < a.length; index++) {
                const element =
                    a[index];
                if (element.parentElement.closest('.cloned')) {
                    element.parentElement.closest('.cloned').remove();
                }
            }
        }
        new Promise((resolve, reject) => {
            $axios.get(`${URL_NOW}/${id}`)
                .then(({
                    data
                }) => {
                    let product = data.data
                    type = 'UPDATE'
                    $("#formTambah")[0].reset();
                    $("#inputID").val(product.id);
                    $("#modalTitle").html('Update Produk');
                    let images = "";
                    countimageinput = 1;
                    product.picture.map((item, i) => {
                        images += `<div class="position-relative mt-2" id="image-${item.id}">
                        <img src="${BASE_URL}/upload/product/${item.image}" alt="${BASE_URL}/upload/product/${item.image}" class="img-fluid" width="300"><button class="btn btn-sm btn-danger hapus position-absolute" style="top:0;right:0" onclick="deleteImage(${item.id})" type="button"><i class="fas fa-trash-alt"></i></button></div>`
                        countimageinput++;
                    });
                    $('#fieldFoto').html(`${images}`)
                    $("#fieldFoto").addClass('d-flex');
                    $('#fieldFoto').show()
                    $("#teksImage").show()
                    $("#selectKategori").val(product.category_id)
                    $("#inputName").val(product.name)
                    $("#inputPrice").val(product.price)
                    $("#inputWight").val(product.weight)
                    $("#inputDescription").val(product.description)
                    $(".inputImage").prop('required', false)
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
    const setStatusProduct = (id, status) => {
        let teks = ``
        if (status) {
            teks = `Ingin mengaktifkan produk ini`
        } else {
            teks = `Ingin menonaktifkan produk ini`
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
                        let url = `{{ route('product.set_status',['id' => ':id']) }}`;
                        url = url.replace(':id', id);
                        $axios.patch(url)
                            .then(({
                                data
                            }) => {
                                toastr.success(data.message.head, data.message.body)
                                refresh_table(URL_NOW)
                            })
                    })
                }
            })
    }

    function deleteImage(id) {
        $swal.fire({
                title: 'Yakin?',
                text: "Anda akan menghapus foto ini?",
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
                        let url = `{{ route('produt_image.delete',['id' => ':id']) }}`;
                        url = url.replace(':id', id);
                        $axios.delete(url)
                            .then(({
                                data
                            }) => {
                                $(`#image-${id}`).remove();
                                toastr.success(data.message.head, data.message.body)
                                refresh_table(URL_NOW);
                            })
                    });

                }
            })
    }
</script>
@endsection