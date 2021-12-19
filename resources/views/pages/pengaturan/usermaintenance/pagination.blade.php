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
   @forelse ($bad_users as $bad_user)
       <input type='hidden' name='user_id[]' value="{{$bad_user['id']}}" />
       <input type='hidden' name='role[]' value="{{$bad_user['role']}}" />
        <tr class='text-center displayer'>
          <th scope='row'>
            {{ ($bad_users->currentpage()-1) * $bad_users->perpage() + $loop->index + 1 }}
          </th>
          <td class='user_name'>{{$bad_user['name']}}</td>
          <td class='user_email'>{{$bad_user['email']}}</td>
          <td class='user_role'>{{$bad_user['role']}}</td>
          <td>
            <span class='badge badge-danger'>Bad</span>
          </td>
          <td>
            <span class='badge badge-danger' style='cursor: pointer;' onclick="showOrderModal('{{$bad_user['id']}},{{(int)$bad_user['status']==1?1:0}},{{$bad_user['role']}}')"><i class='fas fa-thumbs-down'></i></span>
          </td>
        </tr>
   @empty
       <tr>
        <td colspan='5' class='text-center'>Tidak ada data</td>
      </tr>
   @endforelse
  </tbody>
</table>
{!! $bad_users->links() !!}