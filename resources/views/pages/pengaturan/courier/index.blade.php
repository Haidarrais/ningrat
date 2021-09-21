
@extends('layouts.dashboard')

@section('content')
<div class="section-body">
    <div class="card">
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
    if(status) {
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
                $axios.post(`{{ route('courier.set_status') }}`, {id: id, status: status})
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
