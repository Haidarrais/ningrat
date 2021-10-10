<table class="table table-sm text-center">
    <thead>
        <tr>
            <th scope="col">#</th>
            <th scope="col">Nama</th>
            <th scope="col">Kategori</th>
            <th scope="col">Harga</th>
            <th scope="col">Terjual</th>
            <th scope="col">Berat</th>
            <th scope="col">Foto</th>
            <th scope="col">Aksi</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($products as $key => $value)
        <tr>
            <th scope="row">{{ ($products->currentpage()-1) * $products->perpage() + $loop->index + 1 }}</th>
            <td>{{ $value->name??"" }}</td>
            <td>{{ $value->category->name??"" }}</td>
            <td>Rp. {{ number_format($value->price??0) }}</td>
            <td>{{ count($value->buyed??0) }}</td>
            <td>{{ $value->weight }}</td>
            @forelse  ($value->onePicture as $key => $item)
                @if ($key<1)
                <td><img src="{{ asset('upload/product/').'/'.$item->image??'' }}" alt="{{ $value->image }}" class="img-fluid" width="100"></td>
                @endif
            @empty
                    <td >Belum ada foto</td>
            @endforelse
            <td scope="row">
                @if ($value->status)
                <button class="btn-sm btn btn-warning btn-info" title="non aktifkan" onclick="setStatusProduct({{ $value->id }}, 0)"><i class="fas fa-check-circle"></i></button>
                @else
                <button class="btn-sm btn btn-warning" onclick="setStatusProduct({{ $value->id }}, 1)" title="aktifkan"><i class="fas fa-times-circle"></i></button>
                @endif
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