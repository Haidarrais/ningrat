@extends('layouts.dashboard')

@section('content')
<div class="section-body">
    <div class="card">
        <div class="card-header">
            <div class="row">
                <div class="input-group mb-3">
                    <div class="input-group mb-3">
                        <select name="product_category" id="product_category" class="form-control" onchange="onchangeProductType(this.value)">
                            <option selected disabled value="first">== Filter Status ==</option>
                            <option value="aktif">Aktif</option>
                            <option value="tidak aktif">Tidak Aktif</option>
                            <option value="">Tampilkan Semua</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="ml-auto">
                <form action="" method="get" class="row">
                    <div class="input-group mb-3">
                        <input type="text" class="form-control" name="keyword" placeholder="Nama user" value="{{ request()->keyword ?? '' }}">
                        <div class="input-group-append">
                            <button class="btn btn-primary ml-2"><i class="fas fa-search"></i>Cari</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <div class="card-body table-responsive" id="table_data">
            @include('pages.pengaturan.courier.pagination')
        </div>
    </div>
</div>
@endsection

@section('js')
<script>
    const setStatusCourier = (id, status) => {
        let teks = ``
        if (status) {
            teks = `Ingin mengaktifkan Kurir ini`
        } else {
            teks = `Ingin menonaktifkan Kurir ini`
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
                        $axios.post(`{{ route('courier.set_status') }}`, {
                                id: id,
                                status: status
                            })
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

    function onchangeProductType(id) {
        let arrayH = $(".filter").toArray();
        let checkMatching = [];
        console.log(id.toLowerCase());
        const warnP = document.createElement('p');
        for (let index = 0; index < arrayH.length; index++) {
            //    console.log(arrayH[index].innerHTML.toLowerCase());
            if (arrayH[index].innerHTML.toLowerCase() === "") {
                return;
            }
            if (id.toLowerCase() === "") {
                arrayH[index].parentElement.parentElement.classList.remove('d-none');
                let warnElement = document.getElementById("showWarn");
                if (warnElement !== null) {
                    let tbody = document.getElementById("tbody");
                    tbody.removeChild(warnElement.parentNode);
                }
                continue;
            }
            if (arrayH[index].innerHTML.toLowerCase() !== id.toLowerCase()) {
                arrayH[index].parentElement.parentElement.classList.add('d-none');
                checkMatching.push(false);
            } else {
                arrayH[index].parentElement.parentElement.classList.remove('d-none');
                checkMatching.push(true);
            }
            let warnElement = document.getElementById("showWarn");
            if (!checkMatching.includes(true)) {
                if (warnElement === null) {
                    warnP.innerHTML = `<p id="showWarn" class="text-center">Data tidak ditemukan</p>`;
                    arrayH[index].parentElement.parentElement.parentElement.appendChild(warnP);
                }
            } else {
                if (warnElement) {
                    let tbody = document.getElementById("tbody");
                    tbody.removeChild(warnElement.parentNode);
                }
            }
        }
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