<div class="table-responsive">
    <table class="table table-border table-hover">
        <thead>
            <tr>
                <th>No</th>
                <th>Reward</th>
                <th>Status</th>
                <th>Tanggal Pengajuan</th>
                <th>Tanggal Persetujuan</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($user_penukaran->reward()->latest()->get() as $value)
                <tbody>
                    <tr>
                        <td>{{ ++$no }}</td>
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
                                <span class="badge badge-danger">Belum Disetujui / Ditolak</span>
                            @endif
                        </td>
                    </tr>
                </tbody>
            @empty

            @endforelse
        </tbody>
    </table>
</div>
