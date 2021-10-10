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
        <div class="card-header">
          <div class="ml-auto">
            <div class="row">
              <div class="input-group mb-3">
                <!-- <div class="input-group-append">
                  <button class="btn btn-primary"><i class="fas fa-search"></i>Cari</button>
                </div> -->
                <input type="text" class="form-control" name="keyword" placeholder="Cari Nama / email / role" oninput="onchangeProductType(this.value)" value="{{ request()->keyword ?? '' }}">
              </div>
            </div>
          </div>
        </div>
        <div class="col-12 mt-2">
          <ul class="nav nav-tabs col-12">
            <li class="nav-item">
              <a class="nav-link @if(count($userWithRoleAndOrders)>0)active @endif" data-toggle="tab" href="#bad">Bad
                User</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" data-toggle="tab" href="#good">Good User</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" data-toggle="tab" href="#upgrade">Upgrade Request</a>
            </li>
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
      <div id="upgrade" class="container tab-pane">
        <div class="card-body table-responsive" id="table_data2">
          {{-- <div class="tab-content"> --}}
          @include('pages.pengaturan.usermaintenance.upgrade-reqs-pagination')
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
    var urlHere = "{{route('maintenance.show', ": userId ")}}";
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

  function upgrade(id, role) {
    $swal.fire({
      title: 'Yakin?',
      text: "Anda akan menerima permintaan upgrade?",
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
          role: role,
          status_request: true,
          is_upgrade: true
        }).then((response) => {
          let data = response.data;
          $swal.fire({
            icon: data.status ? "success" : "error",
            title: data.message.head,
            text: data.message.body,
          }).then(() => {
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
    });
  }

  function tolak(id, role) {
    $swal.fire({
      title: 'Yakin?',
      text: "Anda akan menolak permintaan upgrade?",
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
          role: role,
          status_request: false,
          is_upgrade: true
        }).then((response) => {
          let data = response.data;
          $swal.fire({
            icon: data.status ? "success" : "error",
            title: data.message.head,
            text: data.message.body,
          }).then(() => {
            new Promise(async (resolve, reject) => {
              $("#table_data2").LoadingOverlay('show')
              await $axios.get(`${URL_NOW}/is_accepting_upgrade_req=true`)
                .then(({
                  data
                }) => {
                  $("#table_data2").LoadingOverlay('hide')
                  $('#table_data2').html(data)
                })
                .catch(err => {
                  console.log(err)
                  $("#table_data2").LoadingOverlay('hide')
                  $swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Something went wrong!',
                  })
                })
            })
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
    });
  }
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
  function onchangeProductType(id) {
    // console.log(isNaN(parseInt(id)));
    let arrayH = $(".user_name").toArray();
    // !isNaN(parseInt(id)) ? categoryFilter(arrayH, id) : nameFilter(arrayH, id);
    // }
    let array_role = $(".user_role").toArray();
    let array_email = $(".user_email").toArray();
    // function nameFilter(arrayH, id) {
    let checkMatching = [];
    const warnP = document.createElement('p');
    for (let index = 0; index < arrayH.length; index++) {
      const item = arrayH[index];
      const role = array_role[index];
      const email = array_email[index];

      if (!(item.innerHTML.toLowerCase().includes(id.toLowerCase())) &&
        !(role.innerHTML.toLowerCase().includes(id.toLowerCase())) &&
        !(email.innerHTML.toLowerCase().includes(id.toLowerCase()))) {
        item.parentElement.classList.add('d-none');
        checkMatching.push(false);
      } else {
        item.parentElement.classList.remove('d-none');
        checkMatching.push(true);
      }
      // let warnElement = document.getElementById("showWarn");
      // if ((!checkMatching.includes(true))) {
      //   if (warnElement === null) {
      //     warnP.innerHTML = `<p id="showWarn" class="text-center">Data tidak ditemukan</p>`
      //     item.parentElement.parentElement.appendChild(warnP);
      //   }
      // } else {
      //   if (warnElement) {
      //     let tbody = document.getElementById("tbody");
      //     tbody.removeChild(warnElement.parentNode);
      //   }
      // }
    }
    // arrayH.map((item, i) => {
    //   // console.log("aaaa", );
    //   if (!(item.innerHTML.toLowerCase().includes(id.toLowerCase()))) {
    //     item.parentElement.classList.add('d-none');
    //     checkMatching.push(false);
    //   } else {
    //     item.parentElement.classList.remove('d-none');
    //     checkMatching.push(true);
    //   }
    //   let warnElement = document.getElementById("showWarn");
    //   if (!checkMatching.includes(true)) {
    //     if (warnElement === null) {
    //       warnP.innerHTML = `<p id="showWarn" class="text-center">Data tidak ditemukan</p>`
    //       item.parentElement.parentElement.appendChild(warnP);
    //     }
    //   } else {
    //     if (warnElement) {
    //       let tbody = document.getElementById("tbody");
    //       tbody.removeChild(warnElement.parentNode);
    //     }
    //   }
    // });
  }
</script>
@endsection