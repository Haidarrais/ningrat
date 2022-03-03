<table class="table table-sm">
  <thead>
    <tr>
      <th>#</th>
      <th>Gambar</th>
      <th>Nama Produk</th>
      <th>Kargori dan variant</th>
      <th>Harga</th>
      <th>Jumlah</th>
      <th>Total</th>
      <th>Keterangan</th>
    </tr>
  </thead>
  <tbody id="tbody" >
    <input type="hidden" name="ongkir-discount" value="0" readonly>
    <input type="hidden" name="discount" value="0" readonly>
    <!-- <input type="hidden" name="discount" value="0" id="discount"> -->
    <input type="hidden" value="0" id="inputWeight">
    @forelse ($products as $key => $value)
    <input type="hidden" name="id[]" value="{{ $value->product_id }}">
    <input type="hidden" name="ongkir-per-category-{{$value->product_id}}" value="{{ $value->product->category->discount->value??0 }}">
    <tr id="displayer">
      <input type="hidden" name="productCategory{{$value->id}}" value="{{ $value->product->category_id }}" class="category_product">
      <td>{{ $loop->iteration }}</td>
      @forelse ($value->product->onePicture as $key => $item)
                  @if ($key<1) <td><img src="{{ asset('upload/product/').'/'.$item->image??'' }}" alt="{{ $value->product->name??''}}.img"
                      class="img-fluid" width="100"></td>
                    @endif
                    @empty
                    <td>Belum ada foto</td>
                    @endforelse
      <td class="product_name" style="width:14%;">{{ $value->product->name??"" }}</td>
      <td style="width: 25%;">Kategori: {{ $value->product->category->subCategory->name??"" }}</br>
                SubKategori: {{ $value->product->category->name??"" }} </br>
                Varian/Sub: {{ $value->product->variant->subVariant->name??"" }}</br>
                SubVarian: {{ $value->product->variant->name??"" }}  </br>
            
            </td> 
      @if ($discount =
      $value->discount()->where('user_id', $value->user_id)->where('status', 1)->first())
      @php
      $status = true;
      if($value->member_price) {
      $price = $value->member_price;
      } else {
      $price = $value->product->price;
      }
      if($discount) $price = App\Traits\SettingTrait::getDiscount($price, $discount->discount );
      $user_discount = $value->discount()->where('user_id', $value->user_id)->where('status', 1)->first();
      if($user_discount) {
      $price = App\Traits\SettingTrait::getDiscount($price, $user_discount->discount);
      }
      @endphp
      @else
      @php
      $status = false;
      if($value->member_price) {
      $price = $value->member_price;
      } else {
      $price = $value->product->price;
      }
      @endphp
      @endif
      <td id="field-price-{{ $value->product_id }}" data-weight="{{ $value->product->weight }}" data-price="{{ $price }}">
        @if ($status)
        {{-- @if ($value->member_price)
                                            <s>Rp. {{ number_format($value->member_price) }}</s><br>
        @else --}}
        <s>Rp. {{ number_format($value->product->price) }}</s><br>
        {{-- @endif --}}
        @endif
        Rp. {{ number_format($price) }}
      </td>
      <td style="width:11%">
        <!-- <div class="row">

          <div class="col-12"> -->
            <input name="qty[]" oninput="onchangePrice({{ $value->product_id }}, '{{$value->stock}}')" type="number" id="total-{{ $value->product_id }}" class="form-control qty text-center" value="0" min="0">
          <!-- </div>

        </div> -->
      </td>
      <input type="hidden" name="price[]" id="input-total-{{ $value->product_id }}">
      <td id="field-total-{{ $value->product_id }}" class="field-total" style="width:15%;">-</td>
      <td> 
        
            <textarea name="note[]"  id="note-{{ $value->product_id }}" class="form-control text-center"></textarea>

        </td>
    </tr>
    @empty
    <tr>
      <td colspan="6">Tidak ada data</td>
    </tr>
    @endforelse
  </tbody>
</table>