<table class="table table-sm">
  <thead>
    asu
    <tr class="text-center">
      <th scope="col">#</th>
      <th scope="col">Nama</th>
      <th scope="col">Role</th>
      <th scope="col">Upgrade Ke Role</th>
      <!-- <th scope="col">Status</th> -->
      <th scope="col">Aksi</th>
    </tr>
  </thead>
  <tbody>
    @php
    $isEmpty = [];
    foreach ($request_upgrades as $key => $value) {
    $index = ++$key;
    if (count($request_upgrades)>0) {
    $role = "${value['role']}";
    $params1 = "upgrade(".$value->user->id.","."'".$value->user->getRoleNames()->first()."'".")";
    $params2 = "tolak(".$value->user->id.","."'".$value->user->getRoleNames()->first()."'".")";
    // $params = "showOrderModal(".$value[`id`].".",".".(int)($value[`status`]) .",".$role.")";
    echo"<tr class='text-center'>
      <th scope='row'>
        $index
      </th>
      <td>".$value->user->name."</td>
      <td>".$value->user->getRoleNames()->first()."</td>
      <td>".$value->role->name."</td>
      <td>
        <span class='badge badge-success' title='Terima' style='cursor: pointer;' onclick=$params1><i class='fas fa-arrow-up'></i></span>
        <span class='badge badge-danger' title='Tolak' style='cursor: pointer;' onclick=$params2><i class='fas fa-times'></i></span>
      </td>
    </tr>";
    array_push($isEmpty, false);
    }
    else {
    array_push($isEmpty, true);
    }
    }
    if (!in_array(false, $isEmpty)) {
    echo"<tr>
      <td colspan='5' class='text-center'>Tidak ada data</td>
    </tr>";
    }
    @endphp
  </tbody>
</table>