<table class="table table-sm">
    <thead>
        <tr>
            <th>#</th>
            <th>Gambar</th>
            <th>Nama Produk</th>
            <th>Kategori dan varian</th>
            <th class="qty">Harga</th>
            <th>Jumlah</th>
            <th>Total</th>
            <th>Keterangan</th>
        </tr>
    </thead>
    <tbody id="tbody">
        <!-- <input type="hidden" value="0" name="discount" id="discount"> -->
        <input type="hidden" value="0" id="inputWeight">
        @forelse ($products as $key => $value)
        <input type="hidden" name="id[]" value="{{ $value->product_id }}">
        <tr id="displayer">
            <input type="hidden" name="ongkir-per-category-{{$value->product_id}}" value="{{$value->product->category->discount->value??0}}">
            <input type="hidden" class="category_product" name="productCategory{{$value->id}}" value="{{ $value->product->category_id }}" id="category_product">
            <td>{{ $loop->iteration }}</td>
            @forelse ($value->product->onePicture as $key => $item)
            @if ($key<1) <td><img src="{{ asset('upload/product/').'/'.$item->image??'' }}" alt="{{ $value->product->name??''}}.img" class="img-fluid" width="100"></td>
                @endif
                @empty
                <td>Belum ada foto</td>
                @endforelse
                <td class="product_name" style="width:14%;">{{ $value->product->name??"" }}</td>
                <td style="width: 25%;">Kategori: {{ $value->product->category->subCategory->name??"" }}</br>
                    SubKategori: {{ $value->product->category->name??"" }} </br>
                    Varian/Sub: {{ $value->product->variant->subVariant->name??"" }}</br>
                    SubVarian: {{ $value->product->variant->name??"" }} </br>

                </td>
                <td id="field-price-{{ $value->product_id }}" data-weight="{{ $value->product->weight }}" data-price="{{ $value->product->price }}">
                    Rp.{{ number_format($value->product->price??0) }}
                </td>
                <td style="width:11%">
                    <input name="qty-old[]" type="hidden" id="total-old-{{ $value->product_id }}" class="form-control qty text-center" value="0" min="0">

                    <input name="qty[]" oninput="onchangePrice({{ $value->product_id }}, '{{$value->stock}}')" type="number" id="total-{{ $value->product_id }}" class="form-control qty text-center"  value="0" min="0">
                </td>
                <input type="hidden" name="price[]" id="input-total-{{ $value->product_id }}">
                <td id="field-total-{{ $value->product_id }}" class="field-total" style="width:11%">-</td>
                <td>
                    <!-- <div class="row"> -->

                        <!-- <div class="col-12"> -->
                            <textarea name="note[]"  id="note-{{ $value->product_id }}" class="form-control text-center"></textarea>
                        <!-- </div> -->

                    </div>
                </td>
        </tr>
        @empty
        <tr>
            <td colspan="6">Tidak ada data</td>
        </tr>
        @endforelse
    </tbody>
</table>