<table class="table table-sm">
    <thead>
        <tr>
            <th scope="col">#</th>
            <th scope="col">Nama Member</th>
            <th scope="col">Alamat</th>
            <th scope="col">Total Harus Dibayar</th>
            <th scope="col">Diskon</th>
            <th scope="col">Ongkir</th>
            <th scope="col">Subsidi Ongkir</th>
            <th scope="col">Status</th>
            <th scope="col">Aksi</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($orders as $key => $value)
        <tr>
            <th scope="row">{{ ($orders->currentpage()-1) * $orders->perpage() + $loop->index + 1 }}</th>
            <td>{{ $value->member_name??"" }}</td>
            <td>{{ $value->member_address??"" }}</td>
            
            <td>Rp. {{ number_format($value->subtotal-($value->subtotal * $value->discount/100) + ($value->cost - $value->subsidy_cost)) }}</td>
            <td>{{$value->discount??0}}%</td>
            <td>Rp. {{number_format($value->cost??0)}}</td>
            <td>Rp. {{number_format($value->subsidy_cost??0)}}</td>
            <td>
                @if ($value->status == 0)
                <span class="badge badge-warning">Pending</span>
                @elseif($value->status == 1)
                <span class="badge badge-secondary">Dikemas</span>
                @elseif($value->status == 2)
                <span class="badge badge-primary">Dikirim</span>
                @elseif($value->status == 3)
                <span class="badge badge-info">Diterima</span>
                @elseif($value->status == 4)
                <span class="badge badge-success">Selesai</span>
                @elseif($value->status == 5)
                <span class="badge badge-danger">Ditolak</span>
                @elseif($value->status == 6)
                <span class="badge badge-danger">Batal</span>
                @endif
            </td>
            <td scope="row">
                @if ($value->status == 0)
                @if ($value->user_id != auth()->id())
                <button class="btn btn-sm btn-primary" onclick="kemas({{ $value->id }})">Kemas Sekarang</button>
                <button class="btn btn-sm btn-danger" onclick="tolak({{ $value->id }})">Tolak Order</button>
                @endif
                @if ($value->user_id == auth()->id())
                <button class="btn btn-sm btn-danger" onclick="batalkan({{ $value->id }})">Batalkan</button>
                @endif
                @elseif($value->status == 1)
                @if ($value->user_id != auth()->id())
                <button class="btn btn-sm btn-info" onclick="set_resi({{ $value->id }})">Set Resi</button>
                <button class="btn btn-sm btn-warning" onclick="kirim({{ $value->id }})">Kirimkan</button>
                @endif
                @if ($value->user_id == auth()->id())
                <button class="btn btn-sm btn-warning" onclick="barang_diterima({{ $value->id }})">Barang Diterima</button>
                @endif
                @elseif($value->status == 2)
                @if ($value->user_id == auth()->id())
                @role('reseller')
                <button class="btn btn-sm btn-info" onclick="set_resi({{ $value->id }})">Set Resi</button>
                @endrole
                <button class="btn btn-sm btn-success" onclick="selesai({{ $value->id }})">Selesai Order</button>
                @endif
                @elseif($value->status == 3)
                @endif
                <button class="btn btn-sm btn-info" onclick="detail({{ $value->id }})">Detail Order</button>
            </td>
        </tr>
        @empty
        <tr>
            <td colspan="6" class="text-center">Tidak ada data</td>
        </tr>
        @endforelse
    </tbody>
</table>

{{ $orders->appends($data)->links() }}