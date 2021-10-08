<table class="table table-sm text-center">
    <thead>
        <tr>
            <th scope="col">#</th>
            <th scope="col">Nama</th>
            <th scope="col">Diskon</th>
            <th scope="col">Status</th>
            <th scope="col">Aksi</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($discounts as $key => $value)
            <tr>
                <th scope="row">{{ ($discounts->currentpage()-1) * $discounts->perpage() + $loop->index + 1 }}</th>
                <td>{{ $value->name??"Unset" }}</td>
                <td>{{ $value->discount??0 }} %</td>
                <td>
                    @if ($value->status == 1)
                        <span class="badge badge-success">Aktif</span>
                    @else
                        <span class="badge badge-danger">Tidak Aktif</span>
                    @endif</td>
                <td scope="row">
                    @if ($value->status)
                        <button class="btn-sm btn btn-warning" onclick="setStatusDiscount({{ $value->id }}, 0)">Non Aktifkan</button>
                    @else
                        <button class="btn-sm btn btn-info" onclick="setStatusDiscount({{ $value->id }}, 1)">Aktifkan</button>
                    @endif
                    <button type="button" class="btn btn-sm btn-success" onclick="editData({{ $value->id }})">Edit</button>
                    <button class="btn btn-sm btn-danger hapus" onclick="deleteData({{ $value->id }})" type="button">Delete</button>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="5">Tidak ada data</td>
            </tr>
        @endforelse
    </tbody>
</table>

{{ $discounts->appends($data)->links() }}
