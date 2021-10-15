<table class="table table-sm">
    <thead>
        <tr>
            <th scope="col">#</th>
            <th scope="col">Nama</th>
            <th scope="col">Kategori</th>
            <th scope="col">Status</th>
            <th scope="col">Aksi</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($variants as $key => $value)
            <tr>
                <th scope="row">{{ ($variants->currentpage()-1) * $variants->perpage() + $loop->index + 1 }}</th>
                <td>{{ $value->name??"unset" }}</td>
                <td>{{$value->category->name??"unset"}}</td>
                <td>
                    @if ($value->id == $value->parent_id)
                    <span class="badge badge-primary">Varian Utama</span>
                    @else
                    <span class="badge badge-secondary">Sub Varian dari : {{$value->subCategory->name ?? "unset"}}</span>
                    @endif
                </td>
                <td scope="row">
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

{{ $variants->appends($data)->links() }}
