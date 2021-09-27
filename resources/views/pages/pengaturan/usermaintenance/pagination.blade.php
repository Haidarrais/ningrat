<table class="table table-sm">
  <thead>
    <tr class="text-center">
      <th scope="col">#</th>
      <th scope="col">Nama</th>
      <th scope="col">Email</th>
      <th scope="col">Status</th>
      <th scope="col">Aksi</th>
    </tr>
  </thead>
  <tbody>
    @forelse ($userWithRoleAndOrders as $key => $value)
    <tr class="text-center">
      <th scope="row">
        {{$key+1}}
      </th>
      <td>{{ $value["name"] }}</td>
      <td>{{ $value["email"] }}</td>
      <td>
        @if ($value["status"])
        <span class="badge badge-success">Good</span>
        @else
        <span class="badge badge-danger">Bad</span>
        @endif
      </td>
      <td>
        @if ($value["status"])
        <span class="badge badge-success" style="cursor: pointer;" onclick="showOrderModal('{{ $value['id'] }}','{{ $value['status'] }}')"><i class="fas fa-thumbs-up"></i></span>
        @else
        <span class="badge badge-danger" style="cursor: pointer;" onclick="showOrderModal('{{ $value['id'] }}','{{ $value['status'] }}')"><i class="fas fa-thumbs-down"></i></span>
        @endif
      </td>
    </tr>
    @empty
    <tr>
      <td colspan="5" class="text-center">Tidak ada data</td>
    </tr>
    @endforelse
  </tbody>
</table>