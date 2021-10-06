<table class="table table-sm">
    <thead>
        <tr>
            <th scope="col">#</th>
            <th scope="col">Nama</th>
            <th scope="col">Total Produk</th>
            <th scope="col">Aksi</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($categories as $key => $value)
            <tr>
                <th scope="row">{{ ($categories->currentpage()-1) * $categories->perpage() + $loop->index + 1 }}</th>
                <td>{{ $value->name }}</td>
                <td>{{ count($value->product) }}</td>
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

{{ $categories->appends($data)->links() }}
