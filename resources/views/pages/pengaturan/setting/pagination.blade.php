<table class="table table-sm">
    <thead>
        <tr>
            <th scope="col">#</th>
            <th scope="col">Key</th>
            <th scope="col">Minimal Transaksi / Bulan</th>
            <!-- <th rowspan="2">(status selesai)</th> -->
            <th scope="col">Minimal Belanja / Order</th>
            <th scope="col">Role</th>
            <th scope="col">Aksi</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($settings as $key => $value)
        <tr>
            <th scope="row">{{ ($settings->currentpage()-1) * $settings->perpage() + $loop->index + 1 }}</th>
            <td>{{ $value->new_key }}</td>
            <td>{{ $value->value }}</td>
            <td>{{ $value->minimal_transaction }}</td>
            <td>{{ $value->role??"-" }}</td>
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

{{ $settings->appends($data)->links() }}