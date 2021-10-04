@extends('layouts.dashboard')
@section('css')
<style>
  .break-it {
    white-space: pre-wrap;
  }
</style>
@endsection
@section('content')
<div class="section-body">
  <div class="card">
    <div class="card-header">
      <div class="row w-100">
        <!-- <div class="col-12">
          <div class="row">
            <div class="ml-auto col-6">
              {{-- <form action="" method="get" class="row"> --}}
              <div class="input-group mb-3">
                <input type="text" class="form-control" name="keyword" placeholder="Kata Kunci"
                  value="{{ request()->keyword ?? '' }}">
                <div class="input-group-append">
                  <button class="btn btn-primary ml-2"><i class="fas fa-search"></i>Cari</button>
                </div>
              </div>
            </div>
          </div>
        </div> -->
        <div class="col-12 mt-2">
          <ul class="nav nav-tabs col-12">
            <li class="nav-item">
              <a class="nav-link @if(count($userWithRoleAndOrders)>0)active @endif" data-toggle="tab" href="#bad">Bad
                User</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" data-toggle="tab" href="#good">Good User</a>
            </li>
            <!-- <li class="nav-item">
              <a class="nav-link" data-toggle="tab" href="#transfer">Transaksi Transfer</a>
            </li> -->
          </ul>
        </div>
      </div>
    </div>
    <div class="tab-content">
      <div id="bad" class="container active tab-pane">
        <form id="form-downgrade-once">
          @csrf
          <div class="card-body table-responsive" id="table_data">
            @include('pages.pengaturan.usermaintenance.pagination')
          </div>
          <button id="buttonSubmit" type="submit" class="btn btn-danger ml-2">Downgrade All</button>
        </form>
      </div>
      <div id="good" class="container tab-pane">
        <div class="card-body table-responsive">
          {{-- <div class="tab-content"> --}}
          @include('pages.pengaturan.usermaintenance.pagination-good-user')
        </div>
      </div>
    </div>
  </div>
</div>
@endsection

@section('modal')
<div class="modal fade" role="dialog" id="modal_edit" data-backdrop="static" data-keyboard="false" tab-index="-1">
</div>
@endsection

@section('js')
<script src="{{ asset('assets-dashboard/js/page/bootstrap-modal.js') }}"></script>
<script id="a">
  let monthHere = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli ', 'Augustus', 'September', 'Oktober', 'November', 'Desember'];
  let type;
  $(document).ready(function() {
    // $("form").on('submit',function(e){e.preventDefault()})
    //   $("#form-downgrade-once").on('submit', function(e){
    //   e.preventDefault();
    //   alert("aa");
    // refresh_table(URL_NOW);
    //   });

    //   $("#submitForm").click(function(){
    //     $("#buttonSubmit").click();
    // });
    // $("#submitForm").on('click',function(){

  });
  // console.log(monthHere);
  async function showOrderModal(id, status, role) {
    console.log(id);
    let tempdata = ['100', '1111']
    var urlHere = "{{route('maintenance.show', ":userId ")}}";
    urlHere = urlHere.replace(':userId', id);
    let html = "";
    let buttons = status ? `<button type="button" onclick="removechilderen()" class="btn btn-outline-primary" data-dismiss="modal">Close</button>` : `<button type="button" onclick="downGradeUser(${id},'${role}')" class="btn btn-outline-danger">Downgrade Now!</button> <button type="button" onclick="removechilderen()" class="btn btn-outline-primary" data-dismiss="modal">Close</button>`
    await $axios.get(urlHere).then((response) => {
      let i = 0;
      let data = response.data;
      for (const property in data) {
        i++;
        console.log(`${property}: ${data[property].sums}`);
        html += `<tr><td>${monthHere[property-1]}</td>
        <td>Rp  ${parseInt(data[property].sums).toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,')}</td></tr>
        `;
      }
    });
    $("#modal_edit").append(`<div class="modal-dialog modal-lg" role="document" >
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modal-detail-monthly">Order</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"  onclick="removechilderen()">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="card-body p-0">
                    <div class="table-responsive table-invoice">
                        <table class="table table-striped" id="table_data">
                            <tr>
                                <th>Bulan</th>
                                <th>Total Transaksi</th>
                            </tr>
                          ${html}
                        </table>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
            ${buttons}
            </div>
        </div>
    </div>`);
    $("#modal_edit").modal('show');
  }

  async function downGradeUser(id, role) {
    $swal.fire({
      title: 'Yakin?',
      text: "Anda akan mendowngrade user ini",
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      cancelButtonText: 'Tidak',
      confirmButtonText: 'Ya!'
    }).then(async (result) => {
      if (result.isConfirmed) {
        await $axios.patch(`{{ route('maintenance.update') }}`, {
          id: id,
          role: role
        }).then((response) => {
          let data = response.data;
          $swal.fire({
            icon: data.status ? "success" : "error",
            title: data.message.head,
            text: data.message.body,
          }).then(() => {
            removechilderen();
            refresh_table(URL_NOW);
          });
        }).catch((r, err) => {
          console.log(r.response.data);
          // console.log(err);
          $swal.fire({
            icon: 'error',
            title: r.response.data.message.head,
            text: r.response.data.message.body,
          });
        });
      }
    })
  }

  function removechilderen() {
    $("#modal_edit").modal('hide');
    setTimeout(() => {
      $("#modal_edit").children().remove();
    }, 500);
  }
  $("form").on('submit', (e) => {
    e.preventDefault()
    let serializedData = $("#form-downgrade-once").serialize();
    $swal.fire({
      title: 'Yakin?',
      text: "Anda akan mendowngrade semua user",
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      cancelButtonText: 'Tidak',
      confirmButtonText: 'Ya!'
    }).then((result) => {
      if (result.isConfirmed) {
        new Promise((resolve, reject) => {
          $axios.post(`{{route('maintenance.downgrade_all')}}`, serializedData)
            .then(({
              data
            }) => {
              refresh_table(URL_NOW);
              let html = '';
              var br = document.createElement("br");
              data.message.body.map((item) => {
                html += `${item.name} ${item.status?"Berhasil":"Gagal"} didowngrade!`
              });
  
              // console.log(html);
              toastr.warning(html)
              // $("#swal2-content").appendChild("break-it");
              // $swal.fire({
              //   icon: 'error',
              //   title: 'Oops...',
              //   text: html,
              // });
            })
            .catch(err => {
              throwErr(err)
            })
        })
      }
    })
  });
  // function downGradeAll(){
  // e.preventDefault();

  // }
  // }
  // $("#submitForm").click(function(e){
  //   e.preventDefault();
  //   // console.log("aa");
  //  $("#form-downgrade-once").submit();
  // });
  // function submitForm(){
  //   $("#form-downgrade-once").submit();
  // }
</script>
@endsection