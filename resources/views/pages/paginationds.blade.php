                    <table class="table table-striped" >
                      <tr>
                        <th>Invoice ID</th>
                        <th>Customer</th>
                        <th>Status</th>
                        <th>Tanggal Pesan</th>
                        <th>Action</th>
                      </tr>
                      @forelse ($orders as $key => $value)
                      <tr>
                        <td>{{ $value->invoice }}</td>
                        <td>{{ $value->user->name }}</td>
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
                        <td>{{ Carbon\Carbon::parse($value->created_at)->isoFormat('dddd, D MMMM Y') }}</td>
                        <td><button onclick="detail({{ $value->id }})" class="btn btn-primary btn-sm">Detail</button></td>
                      </tr>
                      @empty
                      <tr>
                        <td colspan="5" class="text-center">Tidak Ada Pesanan</td>
                      </tr>
                      @endforelse
                    </table>
                    {{ $orders->links() }}