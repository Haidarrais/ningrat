<table class="table table-sm">
  <thead>
    <tr class="text-center">
      <th scope="col">#</th>
      <th scope="col">Nama</th>
      <th scope="col">Email</th>
      <th scope="col">Role</th>
      <th scope="col">Status</th>
      <th scope="col">Aksi</th>
    </tr>
  </thead>
  <tbody>
    @forelse ($good_users as $good_user)
    <input type='hidden' name='user_id[]' value="{{$good_user['id']}}" />
    <input type='hidden' name='role[]' value="{{$good_user['role']}}" />
    <tr class='text-center displayer'>
      <th scope='row'>
        {{ ($good_users->currentpage()-1) * $good_users->perpage() + $loop->index + 1 }}
      </th>
      <td class='user_name'>{{$good_user['name']}}</td>
      <td class='user_email'>{{$good_user['email']}}</td>
      <td class='user_role'>{{$good_user['role']}}</td>
      <td>
        <span class='badge badge-success'>Good</span>
      </td>
      <td>
        <span class='badge badge-success' style='cursor: pointer;' onclick="showOrderModal({{$good_user['id']}},{{(int)$good_user['status']==1?1:0}},{{$good_user['role']}})"><i class='fas fas fa-thumbs-up'></i></span>
      </td>
    </tr>";
    @empty
    <tr>
      <td colspan='5' class='text-center'>Tidak ada data</td>
    </tr>
    @endforelse
  </tbody>
</table>

{!! $good_users->links() !!}