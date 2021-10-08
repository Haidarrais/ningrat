<table class="table table-sm">
    <thead>
        <tr>
            <th scope="col">#</th>
            <th scope="col">Nama</th>
            <th scope="col">Harga Point</th>
            <th scope="col">Status</th>
            <th scope="col">Aksi</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($rewards as $key => $value)
            <tr>
                <td scope="row">{{ ($rewards->currentpage()-1) * $rewards->perpage() + $loop->index + 1 }}</td>
                <td>{{ $value->name??'' }}</td>
                <td>{{ $value->point??'' }}</td>
                <td>
                    @if ($value->status)
                        <span class="badge badge-success">Aktif</span>
                    @else
                        <span class="badge badge-danger">Tidak Aktif</span>
                    @endif
                </td>
                <td scope="row">
                    @if ($value->status)
                        <button class="btn-sm btn btn-warning" onclick="setStatus({{ $value->id }}, 0)">Non-Aktifkan</button>
                    @else
                        <button class="btn-sm btn btn-info" onclick="setStatus({{ $value->id }}, 1)">Aktifkan</button>
                    @endif
                    <button type="button" class="btn btn-sm btn-success" onclick="editData({{ $value->id }})">Edit</button>
                    <button class="btn btn-sm btn-danger hapus" onclick="deleteData({{ $value->id }})" type="button">Delete</button>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="5" class="text-center">Tidak ada data</td>
            </tr>
        @endforelse
    </tbody>
</table>

{{ $rewards->appends($data)->links() }}
