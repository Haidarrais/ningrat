<table class="table table-sm">
    <thead>
        <tr>
            <th scope="col">#</th>
            <th scope="col">Produk</th>
            <th scope="col">Kategori</th>
            <th scope="col">Stok</th>
            <th scope="col">Harga Minimal</th>
            <th scope="col">Harga</th>
            <th scope="col">Diskon</th>
            <th scope="col">Harga Akhir</th>
            <th scope="col">Status</th>
            <th scope="col">Status Diskon</th>
            <th scope="col">Aksi</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($stocks as $key => $value)
            <tr>
                <th scope="row">{{ ($stocks->currentpage()-1) * $stocks->perpage() + $loop->index + 1 }}</th>
                <td>{{ $value->product->name??"" }}</td>
                <td>{{ $value->product->category->name??"" }}</td>
                <td>{{ $value->stock??"" }}</td>
                <td>Rp. {{ number_format($value->product->price??0) }}</td>
                @if($value->member_price)
                    @php $price = $value->member_price @endphp
                @else
                    @php $price = $value->product->price @endphp
                @endif
                <td>Rp. {{ number_format($price??0) }}</td>
                <td>{{ $value->discount->discount ?? 0 }} %</td>
                @if(isset($value->discount->discount))
                    @php
                        $discount_price = $price * ($value->discount->discount/100);
                        $price_discount = $price - $discount_price;
                    @endphp
                @else
                    @php $price_discount = $price @endphp
                @endif
                <td>Rp. {{ number_format($price_discount??0) }}</td>
                <td>
                    @if ($value->status == 0)
                        <span class="badge badge-danger">Tidak Aktif</span>
                    @else
                        <span class="badge badge-success">Aktif</span>
                    @endif
                </td>
                <td>
                    @if ($value->discount)
                        @if ($value->discount->status)
                            <span class="badge badge-success">Aktif</span>
                        @else
                            <span class="badge badge-danger">Tidak Aktif</span>
                        @endif
                    @else
                    <span class="badge badge-danger">Tidak Aktif</span>
                    @endif
                </td>
                <td>
                    @if ($value->discount)
                        @if ($value->discount->status)
                            <button class="btn btn-sm btn-danger discount-btn-{{ $value->id }}" onclick="discount({{ $value->id }}, 0)">Nonaktifkan Diskon</button>
                        @else
                            <button class="btn btn-sm btn-success discount-btn-{{ $value->id }}" onclick="discount({{ $value->id }}, 1)">Aktifkan Diskon</button>
                        @endif
                    @endif
                    <button class="btn btn-sm btn-warning" onclick="edit({{ $value->id }})">Edit</button>
                    @if ($value->status == 0)
                        <button class="btn btn-sm btn-success press-btn-{{ $value->id }}" onclick="aktifkan({{ $value->id }})">Aktifkan</button>
                    @else
                        <button class="btn btn-sm btn-danger press-btn-{{ $value->id }}"  onclick="nonaktifkan({{ $value->id }})">Non-Aktifkan</button>
                    @endif
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="10" class="text-center">Tidak ada data</td>
            </tr>
        @endforelse
    </tbody>
</table>

{{ $stocks->appends($data)->links() }}
