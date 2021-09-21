@extends('layouts.dashboard')

@section('content')
<div class="section-body">
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-border table-hover">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>User</th>
                            <th>Reward</th>
                            <th>Status</th>
                            <th>Tanggal Request</th>
                            <th>Tanggal Approve / Reject</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($users as $user)
                            @foreach ($user->reward as $value)
                                <tr>
                                    <td>{{ $no++ }}</td>
                                    <td>{{ $user->name }}</td>
                                    <td>{{ $value->name }}</td>
                                    <td>
                                        @if ($value->pivot->status == 1)
                                        <span class="badge badge-success">Disetujui</span>
                                        @elseif($value->pivot->status == 2)
                                        <span class="badge badge-danger">Ditolak</span>
                                        @else
                                        <span class="badge badge-danger">Belum Disetujui</span>
                                        @endif
                                    </td>
                                    <td>{{ Carbon::parse($value->pivot->created_at)->isoFormat("dddd, D MMMM Y") }}</td>
                                    <td>
                                        @if ($value->pivot->created_at != $value->pivot->updated_at)
                                            {{ Carbon::parse($value->pivot->updated_at)->isoFormat("dddd, D MMMM Y") }}
                                        @else
                                            <span class="badge badge-danger">Belum Disetujui</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if (!$value->pivot->status)
                                            <button onclick="set_status(`{{ $value->pivot->id }}`, `{{ $user->id }}`, `{{ $user->name }}`, `{{ $value->name }}`, 1)" class="btn btn-sm btn-success">Approve</button>
                                            <button onclick="set_status(`{{ $value->pivot->id }}`, `{{ $user->id }}`, `{{ $user->name }}`, `{{ $value->name }}`, 2)" class="btn btn-sm btn-danger">Reject</button>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@section('js')
<script>
    const set_status = (reward_id, user_id, nama_user, nama_reward, status) => {
        let text
        if(status == 1) {
            text = "approve"
        } else {
            text = "reject"
        }
        $swal.fire({
            title: 'Yakin?',
            text: `Ingin ${text} penukaran reward ${nama_user} untuk ${nama_reward}`,
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
                    $axios.post(`{{ route('reward.user.set_status') }}`, {id: reward_id, status: status})
                        .then(({data}) => {
                            toastr.success(data.message.head, data.message.body)
                            setTimeout(() => {
                                window.location.reload()
                            }, 1000);
                        })
                        .catch(err => throwErr(err))
                })
            }
        })
    }
</script>
@endsection
