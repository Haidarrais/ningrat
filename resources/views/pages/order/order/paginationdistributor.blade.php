<table class="table product-table text-center">
    <thead>
        <tr>
            <th>#</th>
            <th>Gambar</th>
            <th>Nama Produk</th>
            <th>Harga</th>
            <th>Jumlah</th>
            <th>Total</th>
        </tr>
    </thead>
    <tbody class="overflow-auto">
        <!-- <input type="hidden" value="0" name="discount" id="discount"> -->
        <input type="hidden" value="0" id="inputWeight">
        @forelse ($products as $key => $value)
        <input type="hidden" name="id[]" value="{{ $value->id }}">
        <input type="hidden" name="productCategory{{$value->id}}" value="{{ $value->category_id }}">
        <tr>
            <td>{{ $loop->iteration }}</td>
            <td><img src="{{ asset('upload/product').'/'.$value->image }}" alt="{{ $value->image }}" class="img-fluid"
                    width="200"></td>
            <td>{{ $value->name }}</td>
            <td id="field-price-{{ $value->id }}" data-weight="{{ $value->weight }}" data-price="{{ $value->price }}">
                Rp.
                {{ number_format($value->price) }}
            </td>
            <td>
                <input name="qty[]" oninput="onchangePrice({{ $value->id }},1000)" type="number"
                    id="total-{{ $value->id }}" class="form-control qty text-center" value="0" min="0">
            </td>
            <input type="hidden" name="price[]" id="input-total-{{ $value->id }}">
            <td id="field-total-{{ $value->id }}" class="field-total">-</td>
        </tr>
        @empty
        <tr>
            <td colspan="6">Tidak ada data</td>
        </tr>
        @endforelse
    </tbody>
</table>
