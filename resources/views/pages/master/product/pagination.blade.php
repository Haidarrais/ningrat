<table class="table table-sm text-center">
    <thead>
        <tr>
            <th scope="col">#</th>
            <th scope="col">Nama</th>
            <th scope="col">Kategori</th>
            <th scope="col">Harga</th>
            <th scope="col">Berat</th>
            <th scope="col">Foto</th>
            <th scope="col">Aksi</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($products as $key => $value)
            <tr>
                <th scope="row">{{ ($products->currentpage()-1) * $products->perpage() + $loop->index + 1 }}</th>
                <td>{{ $value->name }}</td>
                <td>{{ $value->category->name }}</td>
                <td>Rp. {{ number_format($value->price) }}</td>
                <td>{{ $value->weight }}</td>
                <td><img src="{{ asset('upload/product/').'/'. $value->image }}" alt="{{ $value->image }}" class="img-fluid" width="100"></td>
                <td scope="row">
                    <button type="button" class="btn btn-sm btn-success" onclick="editData({{ $value->id }})">Edit</button>
                    <button class="btn btn-sm btn-danger hapus" onclick="deleteData({{ $value->id }})" type="button">Delete</button>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="7">Tidak ada data</td>
            </tr>
        @endforelse
    </tbody>
</table>

{{ $products->appends($data)->links() }}
