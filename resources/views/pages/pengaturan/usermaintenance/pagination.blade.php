
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
    @php
    foreach ($userWithRoleAndOrders as $key => $value) {
      $isEmpty = [];
      $index = ++$key;
    if (count($userWithRoleAndOrders)>0 && $value['status']==false) {
      $role = "${value['role']}";
      $params = "showOrderModal(".$value['id'].",".(int)$value['status'].","."'".$role."'".")";
      // $params = "showOrderModal(".$value[`id`].".",".".(int)($value[`status`]) .",".$role.")";
    echo "<input type='hidden' name='user_id[]' value=${value['id']} />";
    echo "<input type='hidden' name='role[]' value=${value['role']} />";
    echo"<tr class='text-center'>
      <th scope='row'>
        $index
    </th>
    <td>".$value['name']."</td>
    <td> ".$value['email']."</td>
    <td>".$value['role']."</td>
    <td>
      <span class='badge badge-danger'>Bad</span>
    </td>
    <td>
      <span class='badge badge-danger' style='cursor: pointer;' onclick=$params><i class='fas fa-thumbs-down'></i></span>
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
