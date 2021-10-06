<table class="table table-sm">
    <thead>
        <tr>
            <th scope="col">#</th>
            <th scope="col">Nama Kategori</th>
            <th scope="col">Diskon</th>
            <th scope="col">Aksi</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($category_discounts as $key => $value)
        <tr>
            <th scope="row">{{ ($category_discounts->currentpage()-1) * $category_discounts->perpage() + $loop->index + 1 }}</th>
            <td>{{$value->category->name}}</td>
            <td>{{ $value->value }}</td>
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

{{ $category_discounts->appends($data)->links() }}