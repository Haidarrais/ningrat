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
    $isEmpty = [];
    foreach ($userWithRoleAndOrders as $key => $value) {
    $index = ++$key;
    if ($value['status']==false) {
    array_push($isEmpty, false);
    $role = "${value['role']}";
    $params = "showOrderModal(".$value['id'].",".(int)$value['status'].","."'".$role."'".")";
    // $params = "showOrderModal(".$value[`id`].".",".".(int)($value[`status`]) .",".$role.")";
    echo "<input type='hidden' name='user_id[]' value=${value['id']} />";
    echo "<input type='hidden' name='role[]' value=${value['role']} />";
    echo"<tr class='text-center displayer'>
      <th scope='row'>
        $index
      </th>
      <td class='user_name'>".$value['name']."</td>
      <td class='user_email'>".$value['email']."</td>
      <td class='user_role'>".$value['role']."</td>
      <td>
        <span class='badge badge-danger'>Bad</span>
      </td>
      <td>
        <span class='badge badge-danger' style='cursor: pointer;' onclick=$params><i class='fas fa-thumbs-down'></i></span>
      </td>
    </tr>";

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