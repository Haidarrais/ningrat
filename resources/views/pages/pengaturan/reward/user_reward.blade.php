@extends('layouts.dashboard')

@section('content')
<div class="section-body">
    <div class="card">
        <div class="card-header">
           <div class="row">
                <div class="input-group mb-3">
                    <div class="input-group mb-3">
                        <select name="product_category" id="product_category" class="form-control"
                            onchange="onchangeProductType(this.value)">
                        <option selected disabled value="first">== Filter Status ==</option>
                        <option value="Tampilkan Semua">Tampilkan Semua</option>
                        <option value="Belum Disetujui">Belum Disetujui</option>
                        <option value="Disetujui">Disetujui</option>
                        <option value="Ditolak">Ditolak</option></select>
                    </div>
                </div>
            </div>
            <div class="ml-auto">
                <form action="" method="get" class="row">
                    <div class="input-group mb-3">
                        <input type="text" class="form-control" name="keyword" placeholder="Nama user"
                            value="{{ request()->keyword ?? '' }}">
                        <div class="input-group-append">
                            <button class="btn btn-primary ml-2"><i class="fas fa-search"></i>Cari</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive" id="tabled_data">
                <table class="table table-border table-hover">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>User</th>
                            <th>Reward</th>
                            <th>Status</th>
                            <th>Tanggal Request</th>
                            <th>Tanggal Approve / Reject</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="tbody">
                        @foreach ($users as $user)
                        @foreach ($user->reward as $value)
                        <tr>
                            <td>{{ $no++ }}</td>
                            <td>{{ $user->name }}</td>
                            <td>{{ $value->name }}</td>
                            <td>
                                @if ($value->pivot->status == 1)
                                <span class="filter badge badge-success">Disetujui</span>
                                @elseif($value->pivot->status == 2)
                                <span class="filter badge badge-danger">Ditolak</span>
                                @else
                                <span class="filter badge badge-danger">Belum Disetujui</span>
                                @endif
                            </td>
                            <td>{{ Carbon::parse($value->pivot->created_at)->isoFormat("dddd, D MMMM Y") }}</td>
                            <td>
                                @if ($value->pivot->created_at != $value->pivot->updated_at)
                                {{ Carbon::parse($value->pivot->updated_at)->isoFormat("dddd, D MMMM Y") }}
                                @else
                                <span class="badge badge-danger">Belum Disetujui</span>
                                @endif
                            </td>
                            <td>
                                @if (!$value->pivot->status)
                                <button onclick="set_status(`{{ $value->pivot->id }}`, `{{ $user->id }}`, `{{ $user->name }}`, `{{ $value->name }}`, 1)" class="btn btn-sm btn-success">Approve</button>
                                <button onclick="set_status(`{{ $value->pivot->id }}`, `{{ $user->id }}`, `{{ $user->name }}`, `{{ $value->name }}`, 2)" class="btn btn-sm btn-danger">Reject</button>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                        @endforeach
                    </tbody>
                </table>
                {{ $users->links(); }}
            </div>
        </div>
    </div>
</div>
@endsection

@section('js')
<script>
    const set_status = (reward_id, user_id, nama_user, nama_reward, status) => {
        let text
        if (status == 1) {
            text = "approve"
        } else {
            text = "reject"
        }
        $swal.fire({
                title: 'Yakin?',
                text: `Ingin ${text} penukaran reward ${nama_user} untuk ${nama_reward}`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                cancelButtonText: 'Tidak',
                confirmButtonText: 'Ya!'
            })
            .then((result) => {
                if (result.isConfirmed) {
                    new Promise((resolve, reject) => {
                        $axios.post(`{{ route('reward.user.set_status') }}`, {
                                id: reward_id,
                                status: status
                            })
                            .then(({
                                data
                            }) => {
                                toastr.success(data.message.head, data.message.body)
                                setTimeout(() => {
                                    window.location.reload()
                                }, 1000);
                            })
                            .catch(err => throwErr(err))
                    })
                }
            })
    }
    function onchangeProductType(id){
       let arrayH = $(".filter").toArray();
       let checkMatching = [];
       console.log(id.toLowerCase());
        const warnP = document.createElement('p');
       for (let index = 0; index < arrayH.length; index++) { 
        //    console.log(arrayH[index].innerHTML.toLowerCase());
           if (arrayH[index].innerHTML.toLowerCase()==="") 
           { 
               return; 
            } if(id.toLowerCase()==="tampilkan semua") { 
                arrayH[index].parentElement.parentElement.classList.remove('d-none'); 
                let warnElement=document.getElementById("showWarn"); 
                if (warnElement !==null) { 
                    let tbody= document.getElementById("tbody"); 
                    tbody.removeChild(warnElement.parentNode); 
                } 
                continue; 
            } if(arrayH[index].innerHTML.toLowerCase() !==id.toLowerCase()) { 
                arrayH[index].parentElement.parentElement.classList.add('d-none'); 
                checkMatching.push(false); 
            } else{ 
                arrayH[index].parentElement.parentElement.classList.remove('d-none');
                checkMatching.push(true); 
            } 
            let warnElement=document.getElementById("showWarn"); 
            if (!checkMatching.includes(true)) { 
                if (warnElement===null) {
                    warnP.innerHTML=`<p id="showWarn" class="text-center">Data tidak ditemukan</p>`;
                    arrayH[index].parentElement.parentElement.parentElement.appendChild(warnP);
                }
            } else {
                if (warnElement) {
                    let tbody = document.getElementById("tbody");
                    tbody.removeChild(warnElement.parentNode);
                }
            }
        }
    }
$('html').on('click', '.pagination a', function(e) {
    e.preventDefault();
    // console.log($(".pagination a"));
    var url = $(this).attr('href');
    // $swal.fire({
    // title: 'Perhatian!',
    // text: "Pastikan anda sudah memilih semua produk yg anda butuhkan di halaman ini terlebih dahulu",
    // icon: 'warning',
    // showCancelButton: true,
    // confirmButtonColor: '#3085d6',
    // cancelButtonColor: '#d33',
    // cancelButtonText: 'Belum',
    // confirmButtonText: 'Sudah!'
    // }).then((result) => {
    // if (result.isConfirmed) {
    $axios.get(url).then(() => {
    refresh_table(url);
    });
    // }
    // });
    });
</script>
@endsection