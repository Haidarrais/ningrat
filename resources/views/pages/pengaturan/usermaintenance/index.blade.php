@extends('layouts.dashboard')

@section('content')
<div class="section-body">
  <div class="card">
    <div class="card-header">
      <div class="ml-auto">
        <form action="" method="get" class="row">
          <div class="input-group mb-3">
            <input type="text" class="form-control" name="keyword" placeholder="Kata Kunci" value="{{ request()->keyword ?? '' }}">
            <div class="input-group-append">
              <button class="btn btn-primary ml-2"><i class="fas fa-search"></i>Cari</button>
            </div>
          </div>
        </form>
      </div>
    </div>
    <div class="card-body table-responsive" id="table_data">
      @include('pages.pengaturan.usermaintenance.pagination')
    </div>
  </div>
</div>
@endsection

@section('modal')
<div class="modal fade" tabindex="-1" role="dialog" id="modal_edit">
</div>
@endsection

@section('js')
<script src="{{ asset('assets-dashboard/js/page/bootstrap-modal.js') }}"></script>
<script>
  let month = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli ', 'Augustus', 'September', 'Oktober', 'November', 'Desember'];
  let type
  // $(document).ready(function() {
  // console.log(month);
  async function showOrderModal(id, status) {
    console.log(id);
    let tempdata = ['100', '1111']
    var url = "{{route('maintenance.show', ":userId ")}}";
    url = url.replace(':userId', id);
    let html = "";
    let buttons = status ? `<button type="button" onclick="removechilderen()" class="btn btn-outline-primary" data-dismiss="modal">Close</button>` : `<button type="button" onclick="removechilderen()" class="btn btn-outline-danger">Downgrade Now!</button> <button type="button" onclick="removechilderen()" class="btn btn-outline-primary" data-dismiss="modal">Close</button>`
    await $axios.get(url).then((response) => {
      let i = 0;
      let data = response.data;
      for (const property in data) {
        i++;
        console.log(`${property}: ${data[property].sums}`);
        html += `<tr><td>${month[property-1]}</td>
        <td>Rp  ${parseInt(data[property].sums).toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,')}</td></tr>
        `;
      }
    });
    $("#modal_edit").append(`<div class="modal-dialog modal-lg" role="document">
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

  function removechilderen() {
    $("#modal_edit").modal('hide');
    $("#modal_edit").children().remove();
  }
  // }
</script>
@endsection