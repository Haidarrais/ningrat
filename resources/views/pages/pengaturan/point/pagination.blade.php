<table class="table table-sm">
    <thead>
        <tr>
            <th scope="col">#</th>
            <th scope="col">Kategori</th>
            <th scope="col">Minimal Pembelian</th>
            <th scope="col">Point</th>
            <th scope="col">Aksi</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($points as $key => $value)
            <tr>
                <th scope="row">{{ ($points->currentpage()-1) * $points->perpage() + $loop->index + 1 }}</th>
                <td>{{ $value->category->name??'' }}</td>
                <td>{{ $value->min }}</td>
                <td>{{ $value->point }}</td>
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

{{ $points->appends($data)->links() }}
