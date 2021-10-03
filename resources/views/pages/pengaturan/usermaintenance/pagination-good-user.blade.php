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
$index = ++$key;
$isEmpty = false;
if (count($userWithRoleAndOrders)>0 && $value['status']==true) {
$role = "${value['role']}";
$params = "showOrderModal(".$value['id'].",".(int)$value['status'].","."'".$role."'".")";
// $params = "showOrderModal(".$value[`id`].".",".".(int)($value[`status`]) .",".$role.")";
echo"<tr class='text-center'>
  <th scope='row'>
    $index
  </th>
  <td>".$value['name']."</td>
  <td> ".$value['email']."</td>
  <td>".$value['role']."</td>
  <td>
    <span class='badge badge-success'>Good</span>
  </td>
  <td>
    <span class='badge badge-success' style='cursor: pointer;' onclick=$params><i class='fas fa-thumbs-up'></i></span>
  </td>
</tr>";
$isEmpty = false;
}
else {
  $isEmpty = false;
 }
    }
    if ($isEmpty) {
        echo"<tr>
          <td colspan='5' class='text-center'>Tidak ada data</td>
        </tr>";
        }
    @endphp
  </tbody>
</table>