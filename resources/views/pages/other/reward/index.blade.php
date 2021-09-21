@extends('layouts.dashboard')

@section('content')
<div class="section-body">
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-md-12">
                    <h4>Total Point Saat Ini : {{ $user->total_point }}</h4>
                </div>
                <div class="col-md-12">
                    <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <a class="nav-link active" id="pills-penukaran-tab" data-toggle="pill" href="#pills-penukaran" role="tab" aria-controls="pills-penukaran" aria-selected="true">Penukaran Point</a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a class="nav-link" id="pills-riwayat-penukaran-tab" data-toggle="pill" href="#pills-riwayat-penukaran" role="tab" aria-controls="pills-riwayat-penukaran" aria-selected="false">Riwayat Penukaran Point</a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a class="nav-link" id="pills-riwayat-perolehan-tab" data-toggle="pill" href="#pills-riwayat-perolehan" role="tab" aria-controls="pills-riwayat-perolehan" aria-selected="false">Riwayat Perolehan Point</a>
                        </li>
                    </ul>
                </div>
                <div class="col-md-12">
                    <div class="tab-content" id="pills-tabContent">
                        <div class="tab-pane fade show active" id="pills-penukaran" role="tabpanel" aria-labelledby="pills-penukaran-tab">
                            <div class="table-responsive">
                                <table class="table table-border table-hover">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Nama</th>
                                            <th>Harga Point</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($reward as $value)
                                        <tr>
                                            <td scope="row">
                                                {{ ($reward->currentpage()-1) * $reward->perpage() + $loop->index + 1 }}
                                            </td>
                                            <td>{{ $value->name }}</td>
                                            <td>{{ $value->point }}</td>
                                            <td>
                                                @if ($user->total_point >= $value->point)
                                                <button onclick="penukaran({{ $value->id }}, `{{ $value->name }}`, `{{ $value->point }}`)" class="btn btn-success btn-sm">Tukarkan</button>
                                                @else
                                                <button class="btn btn-danger btn-sm">Point Belum Cukup</button>
                                                @endif
                                            </td>
                                        </tr>
                                        @empty

                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="tab-pane fade show" id="pills-riwayat-penukaran" role="tabpanel" aria-labelledby="pills-riwayat-penukaran-tab">
                            <div id="fieldPenukaran"></div>
                        </div>
                        <div class="tab-pane fade show" id="pills-riwayat-perolehan" role="tabpanel" aria-labelledby="pills-riwayat-perolehan-tab">
                            <div id="fieldPerolehan"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('js')
<script>
    window.$paging = false
    const USER_ID = `{{ Auth::id() }}`
    $(document).ready(() => {
        let url_perolehan = `{{ route('page.reward.perolehan', ['user_id' => ':id']) }}`
        url_perolehan = url_perolehan.replace(':id', USER_ID)
        $axios.post(`${url_perolehan}`)
            .then(({data}) => $("#fieldPerolehan").html(data))

        let url_penukaran = `{{ route('page.reward.penukaran', ['user_id' => ':id']) }}`
        url_penukaran = url_penukaran.replace(':id', USER_ID)
        $axios.post(`${url_penukaran}`)
            .then(({data}) => $("#fieldPenukaran").html(data))
    })

    const penukaran = (id, text, point) => {
        $swal.fire({
            title: 'Yakin?',
            text: `Ingin menukarkan dengan ${text} seharga ${point} point!`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            cancelButtonText: 'Tidak',
            confirmButtonText: 'Ya!'
        })
        .then(result => {
            if (result.isConfirmed) {
                $axios.post(`{{ route('page.reward.penukaran_reward') }}`, {
                    id, USER_ID
                })
                    .then(({data}) => {
                        let response = data
                        $swal.fire({
                            icon: 'success',
                            title: response.message.head,
                            text: response.message.body,
                        })
                        setTimeout(() => {
                            window.location.reload()
                        }, 1000);
                    })
                    .catch(err => {
                        throwErr(err)
                    })
            }
        })
    }
</script>
@endsection
