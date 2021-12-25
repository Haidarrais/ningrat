<table class="table table-sm">
  <thead>
    <tr class="text-center">
      <th scope="col">#</th>
      <th scope="col">Nama</th>
      <th scope="col">Email</th>
      <th scope="col">Role</th>
      <th scope="col">Upgrade Ke Role</th>
      <!-- <th scope="col">Status</th> -->
      <th scope="col">Aksi</th>
    </tr>
  </thead>
  <tbody>
    @forelse ($request_upgrades as $idx=>$request_upgrade)
    <tr class='text-center'>
      <th scope='row'>
        {{ ($request_upgrades->currentpage()-1) * $request_upgrades->perpage() + $loop->index + 1 }}
      </th>
      <td class='user_name'>{{$request_upgrade->user->name}}</td>
      <td class='user_email'>{{$request_upgrade->user->email}}</td>
      <td class='user_role'>{{$request_upgrade->user->getRoleNames()->first()}}</td>
      <td class='user_torole'>{{$request_upgrade->role->name}}</td>
      <td>
        <span class='badge badge-success' title='Terima' style='cursor: pointer;' onclick="upgrade('{{$request_upgrade->user->id}},{{$request_upgrade->user->getRoleNames()->first()}}')"><i class='fas fa-arrow-up'></i></span>
        <span class='badge badge-danger' title='Tolak' style='cursor: pointer;' onclick="tolak('{{$request_upgrade->user->id}},{{$request_upgrade->user->getRoleNames()->first()}}')"><i class='fas fa-times'></i></span>
      </td>
    </tr>
    @empty
    <tr>
      <td colspan='5' class='text-center'>Tidak ada data</td>
    </tr>
    @endforelse
  </tbody>
</table>
{!!$request_upgrades->links()!!}