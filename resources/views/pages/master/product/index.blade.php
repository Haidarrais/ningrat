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
                        <select name="" id="selectKategori" class="form-control" required onchange="triggerVariant(event)"></select>
                    </div>
                    <div class="form-group d-none" id="sub_category_container">
                        <label for="selectSubKategori">Sub Kategori</label>
                        <select name="sub_category" id="selectSubKategori" class="form-control" ></select>
                    </div>
                    <div class="form-group" >
                        <label for="selectVariant">Varian</label>
                        <select name="variant_id" id="selectVariant" class="form-control" onchange="triggerSubVariant(event)">
                            <option id="before_chose_category" value="" selected disabled class="">Pilih kategori dulu
                            </option>
                        </select>
                    </div>
                    <div class="form-group d-none" id="sub_variant_container">
                        <label for="selectSubVariant">Sub Varian</label>
                        <select name="sub_variant_id" id="selectSubVariant" class="form-control">
                            <option id="before_chose_variant" value="" selected disabled class="">Pilih variant dulu
                            </option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="inputName">Nama</label>
                        <input type="text" name="name" id="inputName" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="inputPrice">Harga</label>
                        <input type="text" name="price" id="inputPrice" class="form-control" data-a-sign="Rp. "
                            data-a-dec="," data-a-sep="." required>
                    </div>
                    <div class="form-group">
                        <label for="inputWight">Berat</label>
                        <input type="text" name="weight" id="inputWight" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="inputDescription">Deskripsi</label>
                        <textarea name="description" id="inputDescription" cols="30" rows="5"
                            class="form-control"></textarea>
                    </div>
                    <p class="text-danger text-center" id="teksImage" style="display: none">Jangan upload gambar jika
                        tidak ingin mengubahnya</p>
                    <!-- <div class="form-group">
                        <label for="inputImage">Foto Produk</label>
                        <input type="file" name="image" id="inputImage" class="form-control" required>
                    </div> -->
                    <div class="input-group control-group lst increment">
                        <input type="file" name="image[]" id="inputImage" class="myfrm form-control inputImage">
                        <div class="input-group-btn">
                            <button class="btn btn-success btn-success-images" type="button"><i
                                    class="fldemo glyphicon glyphicon-plus"></i>Add</button>
                        </div>
                    </div>
                    <div class="clone hide" hidden>
                        <div class="hdtuto control-group lst input-group" style="margin-top:10px">
                            <input type="file" name="image[]" class="myfrm form-control inputImage">
                            <div class="input-group-btn">
                                <button class="btn btn-danger btn-danger-images" type="button"><i
                                        class="fldemo glyphicon glyphicon-remove"></i> Remove</button>
                            </div>
                        </div>
                    </div>
                    <div id="fieldFoto" style="display: none"
                        class="d-flex flex-wrap justify-content-around flex-grow-1"></div>
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
            // if ($("#before_chose_category")==null) {
                // console.log($("#before_chose_category"));
                // console.log($("#selectKategori"));
                $("#selectVariant").html('<option id="before_chose_category" value="" selected disabled class="">Pilih kategori dulu</option>');
            // }
            $('#modal_tambah').modal('show')
        })

        $('#inputPrice').autoNumeric('init')

        $("#formTambah").on('submit', async (e) => {
            e.preventDefault();
            let FormDataVar = new FormData($("#formTambah")[0]);
            let image = $(".inputImage");
            let tempName = "";
            if($("selectSubCategory").val()===""){
                $swal.fire({
                     title:"Error"
                })
                return;
            }
            $("#modal_tambah").LoadingOverlay('show');
            if (type == "STORE") {
                for (var pair of FormDataVar.entries()) {
                    console.log(pair[0]+ ', ' + pair[1]);
    }
                await new Promise((resolve, reject) => {
                    $axios.post(`{{ route('product.store') }}`, FormDataVar, {
                            headers: {
                                'Content-type': 'multipart/form-data'
                            }
                        })
                        .then(({
                            data
                        }) => {
                            $("#modal_tambah").LoadingOverlay('hide');
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
                            $("#modal_tambah").LoadingOverlay('hide');
                            throwErr(err)
                        })
                })
            } else if (type == "UPDATE") {
                let id_product = $("#inputID").val()
                FormDataVar.append('_method', 'PUT')
                await new Promise((resolve, reject) => {
                    $axios.post(`${URL_NOW}/${id_product}`, FormDataVar, {
                            headers: {
                                'Content-type': 'multipart/form-data'
                            }
                        })
                        .then(({
                            data
                        }) => {
                            $("#modal_tambah").LoadingOverlay('hide');
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
                            $("#modal_tambah").LoadingOverlay('hide');
                            throwErr(err)
                        })
                })
            }
            $("#modal_tambah").LoadingOverlay('hide');
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
                    let option = '<option class="category_choices" value="" disabled selected>== Pilih Kategori ==</option>'
                    $.each(data.data, (i, e) => {
                        if (e.id === e.parent_id) {
                        option += `<option class="category_choices" value="${e.id},${e.have_subs}">${e.name}</option>`
                        }
                    })
                    $('#selectKategori').html(option)
                })
                .catch(err => {
        console.log('refresh cat',err);
                    $swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Something went wrong!',
                    })
                })
        })
    }

    const editData = async id => {
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
        await new Promise(async (resolve, reject) => {
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
                    console.log(product);
                    // $("#selectKategori").val(product.category.id.toString())
                    let categorie_dropdowns = $(".category_choices");
                    let data_id = product.category.id
                    for (let index = 0; index < categorie_dropdowns.length; index++) {
                        const {value} = categorie_dropdowns[index];
                        if (value!=="" ) {
                        let a = value.split(",")[0];
                        if (parseInt(a) === data_id) {
                           categorie_dropdowns[index].selected = true;
                        }
                        }
                    }
                    let variant = product.variant;
                    if (product.variant_id!=null) {
                        let option = `<option selected value="${variant.id}">${variant.name}</option>`;
                        $("#selectVariant").html(option) ;
                    }else{
                        let option = `<option selected value="">Varian belum disetting</option>`;
                        $("#selectVariant").html(option) ;
                    }
                    getVariantsOnly();
                    $("#inputName").val(product.name)
                    $("#inputPrice").val(product.price)
                    $("#inputWight").val(product.weight)
                    $("#inputDescription").val(product.description)
                    $(".inputImage").prop('required', false)
                    $('#modal_tambah').modal('show');

                })
                .catch(err => {
                    console.log('edit data',err)
                    $swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Something went wrong!',
                    })
                })
        });
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
    const triggerVariant = (event) => {
        let {value} = event.target;
        let id = value.split(',')[0];
        let have_subs = value.split(',')[1];
        if (have_subs === "false") {
        console.log('trigger');
            $swal.fire({
                title: 'Oops',
                text: "Kategori ini belum memiliki sub kategori, buatlah sub kategori terlebih dahulu",
                icon: 'error',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                cancelButtonText: 'Pilih kategori lain',
                confirmButtonText: 'Oke!'
            }).then((res)=>{
                if (res.isConfirmed) {
                    if ($("#sub_category_container").hasClass('d-none')) {
                    }else{
                        $("#sub_category_container").addClass('d-none');
                    }
                   window.location = "{{ route('category.index') }}";
                }
            });
            if ($("#sub_category_container").hasClass('d-none')) {
            }else{
            $("#sub_category_container").addClass('d-none');
            }
            // return;
        }else{
            if ($("#sub_category_container").hasClass('d-none')) {
            $("#sub_category_container").removeClass('d-none');
            }
            getSubcategories(id);
        }
    // let option = '<option id="pilih_subKategori" value="" selected disabled>Pilih varian</option>';

    // sub_categories.map((item)=>{
    // if (item.id!==id) {
    // option += `<option value="${item.id}">${item.name}</option>`;
    // });
    
    // $("#selectSubKategori").html(option);
    //get sub categories
    //end categories
    // }
        $("#selectVariant").LoadingOverlay('show');
        new Promise((resolve, reject) => {
        let url = `{{ route('variants_by_product',['id' => ':id']) }}`;
        url = url.replace(':id', id);
        $axios.get(url)
        .then(({
        data
        }) => {
        let option = '<option id="pilih_variant" value="" selected disabled>Pilih varian</option><option id="pilih_variant" value="tanpa">Tanpa variant</option>'
        data.message.data.map((item, i)=>{
                option += `<option value="${item.id}">${item.name}</option>`

        });

        if (data.message.data.length>0) {
        $('#selectVariant').html(option);
        }else{
            $('#selectVariant').html('<option id="pilih_variant" value="" selected disabled>Belum ada variant</option><option id="pilih_variant" value="tanpa">Tanpa variant</option>');
        }
        $("#selectVariant").LoadingOverlay('hide');
        // $('#before_chose_variant').addClass('d-none');
            })
        .catch(err => {
            console.log('trigger data',err);
        $swal.fire({
        icon: 'error',
        title: 'Oops...',
        text: 'Something went wrong!',
        })
        })
        })
        }
        

async function getSubcategories(id) {
    $("#selectSubKategori").LoadingOverlay('show');
   await new Promise((resolve, reject) => {
        let url = `{{ route('api.get_sub_categories',['id' => ':id']) }}`;
        url = url.replace(':id', id);
        $axios.get(url)
        .then(({
        data
        }) => {
let option = '<option id="pilih_subKategori" value="" selected disabled>Pilih Sub Kategori</option>';
    
    data.data.map((item)=>{
    if (item.id!==id) {
    option += `<option value="${item.id}">${item.name}</option>`;
    }
    });
    
        
        if (data.data.length>0) {
       $("#selectSubKategori").html(option);
        }else{
        $('#selectSubKategori').html('<option id="pilih_variant" value="" selected disabled>Belum ada variant</option>');
        }
        $("#selectSubKategori").LoadingOverlay('hide');
        $('#before_chose_category').addClass('d-none');
        })
        .catch(err => {
        console.log('get sub',err);
        $swal.fire({
        icon: 'error',
        title: 'Oops...',
        text: 'Something went wrong!',
        })
        })
        })
}
const triggerSubVariant =async (event) =>{
    let id = event.target.value;
    if (id==="tanpa" && id === "") {
        return;
        $swal.toastr()
    }
    $("#selectSubVariant").LoadingOverlay('show');
        await new Promise((resolve, reject) => {
        let url = `{{ route('api.get_sub_variants',['id' => ':id']) }}`;
        url = url.replace(':id', id);
        $axios.get(url)
        .then(({
        data
        }) => {
        let option = '<option id="pilih_sub_variant" value="" selected disabled>Pilih Sub Kategori</option>';
        
        data.data.map((item)=>{
        if (item.id!==id) {
        option += `<option value="${item.id}">${item.name}</option>`;
        }
        });
        
        
        if (data.data.length>0) {
        $('#sub_variant_container').removeClass('d-none');
        $("#selectSubVariant").html(option);
        }else{
        $('#selectSubVariant').html('<option id="pilih_sub_variant" value="" selected disabled>Belum ada sub variant</option>');
        }
        $("#selectSubVariant").LoadingOverlay('hide');
        $('#sub_variant_container').addClass('d-none');
        })
        .catch(err => {
        console.log('get sub',err);
        $swal.fire({
        icon: 'error',
        title: 'Oops...',
        text: 'Something went wrong!',
        })
        })
        });
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
    const getVariantsOnly = ()=>{
        new Promise((resolve, reject) => {
                let url = "{{ route('api.get_variant')}}";
                $axios.get(url)
                .then(({
                data
                }) => {
                    // console.log(data);
                let option = '<option id="pilih_variant" value="" selected disabled>Pilih varian</option><option id="pilih_variant" value="tanpa">Tanpa variant</option>';
                data.data.map((item, i)=>{
                option += `<option value="${item.id}">${item.name}</option>`
                
                });
                
                if (data.data.length>0) {
                $('#selectVariant').html(option);
                }else{
                $('#selectVariant').html('<option id="pilih_variant" value="" selected disabled>Belum ada variant</option><option id="pilih_variant" value="tanpa">Tanpa variant</option>');
                }
                // $('#before_chose_variant').addClass('d-none');
                })
                .catch(err => {
                console.log('trigger data',err);
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