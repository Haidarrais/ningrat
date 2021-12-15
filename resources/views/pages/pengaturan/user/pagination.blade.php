<table class="table table-sm">
    <thead>
        <tr class="text-center">
            <th scope="col">#</th>
            <th scope="col">Nama</th>
            <th scope="col">Role</th>
            <th scope="col" colspan="2">Data</th>
            <th scope="col">Email</th>
            <th scope="col">Status</th>
            <th scope="col">Aksi</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($users as $key => $value)
            <tr class="text-center">
                <th scope="row">{{ ($users->currentpage()-1) * $users->perpage() + $loop->index + 1 }}</th>
                <td>{{ $value->name??'' }}</td>
                <td>{{ $value->role_name??'' }}</td>
                <td class="text-left">
                    Provinsi : {{ $value->member->city->province->name??'' }}<br>
                    Kota : {{ $value->member->city->name??'' }}<br>
                    Kecamatan : {{ $value->member->subdistrict->subdistrict_name??'' }}<br>
                    Alamat : {{ $value->member->address??'' }}<br>
                    No. Telp. : {{ $value->member->phone_number??'' }}<br>
                    No. WhatsApp : {{ $value->member->nowhatsapp??'' }}<br>
                </td>
                <td class="text-left">
                    Facebook : {{ $value->member->facebook??'' }}<br>
                    Instagram : {{ $value->member->instagram??'' }}<br>
                    Marketplace : {{ $value->member->marketplace??'' }}<br>
                    File Mou : <a href="{{asset('uploads/mou/' . $value->member->mou??'' )}}"></a><br>
                </td>
                <td>{{ $value->email??'' }}</td>
                <td>
                    @if ($value->status)
                        <span class="badge badge-success">Aktif</span>
                    @else
                        <span class="badge badge-danger">Tidak Aktif</span>
                    @endif
                </td>
                <td scope="row">
                    @role('superadmin')
                    @if ($value->status)
                        <button class="btn-sm btn btn-warning" onclick="setStatusUser({{ $value->id }}, 0)"><i class="fas fa-user-slash"></i></button>
                    @else
                        <button class="btn-sm btn btn-info" onclick="setStatusUser({{ $value->id }}, 1)"><i class="fas fa-user-check"></i></button>
                    @endif
                    @endrole
                    <button type="button" class="btn-sm btn btn-success" onclick="editData({{ $value->id }})"><i class="fas fa-user-edit"></i></button>
                    <button class="btn-sm btn btn-danger hapus" onclick="deleteData({{ $value->id }})" type="button"><i class="fas fa-user-times"></i></button>
                    <a href="{{ route('users.hirarki', base64_encode($value->api_token)) }}" class="btn-sm btn btn-warning"><i class="fas fa-code-branch"></i></a>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="5" class="text-center">Tidak ada data</td>
            </tr>
        @endforelse
    </tbody>
</table>

{{ $users->appends($data)->links() }}
