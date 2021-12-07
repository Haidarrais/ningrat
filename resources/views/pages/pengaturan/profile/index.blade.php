@extends('layouts.dashboard')

@section('content')
<div class="row mt-sm-4">
    <div class="col-12 col-md-12 col-lg-5">
        <div class="card profile-widget">
            <div class="profile-widget-header">
                <img alt="image" src="{{ asset('assets-dashboard/img/avatar/avatar-1.png') }}" class="rounded-circle profile-widget-picture">
                <div class="profile-widget-items">
                    <div class="profile-widget-item">
                        <div class="profile-widget-item-label">Total Produk</div>
                        <div class="profile-widget-item-value">{{$stock_total}}</div>
                    </div>
                    <div class="profile-widget-item">
                        <div class="profile-widget-item-label">Total Hirarki</div>
                        <div class="profile-widget-item-value">{{$count_hierarki}}</div>
                    </div>
                    <div class="profile-widget-item">
                        <div class="profile-widget-item-label">Order Selesai</div>
                        <div class="profile-widget-item-value">{{count($orders)}}</div>
                    </div>
                </div>
            </div>
            <div class="profile-widget-description">
                <div class="profile-widget-name">{{ $user->name??"" }}
                    <div class="text-muted d-inline font-weight-normal">
                        <div class="slash"></div> {{ ucfirst($user->getRoleNames()->first()??'') }}
                    </div>
                </div>
                <a href="{{ route('users.hirarki', base64_encode($user->api_token)) }}" class="btn btn-block btn-primary">Hirarki</a>
            </div>
            @php $upgrade = [] @endphp
            @role('distributor|superadmin')
            {{-- Tidak tampil jika superadmin (padahal tidak mungkin) dan distributor karena mereka adalah puncak dari rantai makanan yang ada pada dunia ini --}}
            @else
            <div class="card mt-3">
                <div class="card-body">
                    <ul class="list-group">
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            6 Bulan Member Setelah Upgrade
                            @if ($monthDiffFromLastUpgrade>=6)
                            <span class="badge badge-success badge-pill"><i class="fas fa-check"></i></span>
                            @else
                            <span class="badge badge-danger badge-pill"><i class="fas fa-times"></i></span>
                            @endif
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            Repeat order sejumlah {{rtrim((string)$minimal_transaction, "0")}}jt/bulan selama 6 Bulan
                            @if ($checkMitraRequirement)
                            <span style="cursor: pointer" class="badge badge-success badge-pill showMontlyTransaction">
                                <i class="fas fa-check"></i></span>
                            @else
                            <span style="cursor: pointer" class="badge badge-danger badge-pill showMontlyTransaction"><i class="fas fa-times"></i></span>
                            @endif
                        </li>
                    </ul>
                </div>
                <div class="card-footer" id="fieldUpgrade">
                    @if ($monthDiffFromLastUpgrade<6) <p>Tunggu {{6 - $monthDiffFromLastUpgrade}} bulan untuk request upgrade</p>
                        @else
                        @if (!$checkMitraRequirement)
                        <button class="btn btn-warning">Anda belum bisa upgrade</button>
                        @else
                        <button class="btn btn-success" id="btnUpgrade" onclick="upgrade({{ $user->id }})">Upgrade</button>
                        @endif
                        @endif
                </div>
            </div>
            @endrole
        </div>
    </div>
    <div class="col-12 col-md-12 col-lg-7">
        <div class="card">
            <form method="post" class="cardUpdateProfile" id="form-data">
                <div class="card-header">
                    <h4>Edit Profile</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="form-group col-md-6 col-12">
                            <label>Nama</label>
                            <input type="text" class="form-control" name="name" value="{{ $user->name }}" required="">
                            <div class="invalid-feedback">
                                Please fill in the first name
                            </div>
                        </div>
                        <div class="form-group col-md-6 col-12">
                            <label>Email</label>
                            <input type="text" class="form-control" name="email" value="{{ $user->email }}" required="">
                            <div class="invalid-feedback">
                                Please fill in the last name
                            </div>
                        </div>
                        <div class="form-group col-md-6 col-12">
                            <label>Kode Referal</label>
                            <input type="text" class="form-control" name="kode_referal" value="{{ $user->kode_referal }}" required="">
                            <div class="invalid-feedback">
                                Please fill in the first name
                            </div>
                        </div>
                        <div class="form-group col-md-6 col-12">
                            <label>Nomer Handphone</label>
                            <input type="number" class="form-control" name="phone_number" value="{{ $user->member->phone_number ?? '' }}" required="">
                            <div class="invalid-feedback">
                                Please fill in the first name
                            </div>
                        </div>
                    </div>
                    <p class="text-danger text-center" id="teksPassword">Kosongkan jika tidak merubah password</p>
                    <div class="row">
                        <div class="form-group col-md-6 col-12">
                            <label>Password</label>
                            <input type="password" class="form-control" name="password" value="">
                            <div class="invalid-feedback">
                                Please fill in the email
                            </div>
                        </div>
                        <div class="form-group col-md-6 col-12">
                            <label>Konfirmasi Password</label>
                            <input type="password" name="password_confirmation" class="form-control" value="">
                        </div>
                    </div>
                    <div class="text-center" id="loading" style="display: none">
                        <div class="lds-heart">
                            <div></div>
                        </div>
                    </div>
                    <div class="row" id="fieldAlamat">
                        <div class="form-group col-12">
                            <label for="">Provinsi</label>
                            <select name="province_id" id="province_id">
                                <option value="" selected disabled>== Pilih Povinsi ==</option>
                            </select>
                        </div>
                        <div class="form-group col-12">
                            <label for="">Kabupaten/Kota</label>
                            <select name="city_id" id="city_id">
                                <option value="" selected disabled>== Pilih Kabupaten/Kota ==</option>
                            </select>
                        </div>
                        <div class="form-group col-12">
                            <label for="">Kecamatan</label>
                            <select name="subdistrict_id" id="subdistricts_id">
                                <option value="" selected disabled>== Pilih Kecamatan ==</option>
                            </select>
                        </div>
                        <div class="form-group col-12">
                            <label>Alamat lengkap</label>
                            <textarea name="address" id="address" cols="30" rows="7" class="form-control">{{ $user->member->address ?? '' }}</textarea>
                        </div>
                    </div>

                </div>
                <div class="card-footer text-right">
                    <button class="btn btn-primary" type="submit">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
@section('modal')
<div class="modal fade" id="modal-detail-monthly" tabindex="-2" role="dialog" aria-labelledby="modal-detail-monthly" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modal-detail-monthly">Order</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="card-body p-0 overflow-auto">
                    <div class="table-responsive table-invoice">
                        <table class="table table-striped" id="table_data">
                            <tr>
                                <th>Bulan</th>
                                <th>Total Transaksi</th>
                                <th>Status</th>
                            </tr>
                            @foreach ($monthly_transaction as $index => $item)
                            <tr>
                                <td>{{ $month[$index - 1] }}</td>
                                <td>{{"Rp " . number_format($item["sums"],2,',','.')}}</td>
                                <td>
                                    <i class="  @if ($item['sums'] > $minimal_transaction)
                                                          fas fa-thumbs-up text-success
                                                          @else fas fa-thumbs-down text-danger
                                                      @endif" style="font-size: 20px"></i>
                                </td>
                            </tr>
                            @endforeach
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-primary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
@endsection
@section('css')
<link rel="stylesheet" href="{{ asset('vendor/select2/css/select2.min.css') }}">
<link rel="stylesheet" href="{{ asset('css/loader.css') }}">
@endsection

@section('js')
<script src="{{ asset('vendor/select2/js/select2.min.js') }}"></script>
<script>
    const OLD_PROVINCE = `{{ $user->member->city->province->province_id ?? '' }}`
    const OLD_CITY = `{{ $user->member->city_id ?? '' }}`
    const OLD_SUBDISTRICT = `{{ $user->member->subdistrict_id ?? '' }}`
    $(document).ready(() => {
        // province_id city_id subdistricts_id
        $('#province_id').select2()
        $('#city_id').select2()
        $('#subdistricts_id').select2()
        local_loading()
        get_province(OLD_PROVINCE).then(() => {
            if (OLD_CITY != 0) {
                get_city(OLD_PROVINCE, OLD_CITY).then(() => {
                    if (OLD_SUBDISTRICT != 0) {
                        get_subdistrict(OLD_CITY, OLD_SUBDISTRICT)
                    }
                    local_unloading()
                })
            } else {
                local_unloading()
            }
        })

        $("#province_id").on('change', () => {
            let province_id = $("#province_id").val()
            local_loading()
            get_city(province_id).then(() => local_unloading())
        })

        $("#city_id").on('change', () => {
            let city_id = $("#city_id").val()
            local_loading()
            get_subdistrict(city_id).then(() => local_unloading())
        })

        $("#form-data").on('submit', e => {
            e.preventDefault()
            loading('show', '.cardUpdateProfile', {
                image: '',
                text: 'Proses Update.....'
            })
            new Promise((resolve, reject) => {
                $axios.patch(`{{ route('profile.update') }}`, $("#form-data").serialize())
                    .then(({
                        data
                    }) => {
                        loading('hide', '.cardUpdateProfile')
                        $swal.fire({
                            icon: 'success',
                            title: data.message.head,
                            text: data.message.body
                        })
                    })
                    .catch(err => {
                        loading('hide', '.cardUpdateProfile')
                        throwErr(err)
                    })
            })
        })
    })

    const local_loading = () => {
        loading('show', '#fieldAlamat', {
            image: '',
            text: "Harap Tunggu"
        })
        $("#loading").show()
    }

    const local_unloading = () => {
        loading('hide', '#fieldAlamat', {
            image: '',
            text: "Harap Tunggu"
        })
        $("#loading").hide()
    }
    $(".showMontlyTransaction").on("click", () => {
        $("#modal-detail-monthly").modal("show");
    })
    const get_province = (id = null) => {
        return new Promise((resolve, reject) => {
            $axios.get(`{{ route('api.get_provice') }}`)
                .then(({
                    data
                }) => {
                    let result = data.data
                    $("#province_id").empty()
                    let html = `<option value="" selected disabled>== Pilih Povinsi ==</option>`
                    $.each(result, (id_elemnt, el) => {
                        if (el.province_id == id) {
                            html += `<option value="${el.province_id}" selected>${el.name}</option>`
                        } else {
                            html += `<option value="${el.province_id}">${el.name}</option>`
                        }
                    })
                    $("#city_id").html(`<option value="" selected disabled>== Pilih Kabupaten/Kota ==</option>`)
                    $("#subdistricts_id").html(`<option value="" selected disabled>== Pilih Kecamatan ==</option>`)
                    $("#province_id").html(html)
                    resolve(result)
                })
                .catch(err => {
                    console.log(err)
                    reject(err)
                })
        })
    }

    const get_city = (id, id_city = null) => {
        return new Promise((resolve, reject) => {
            $axios.get(`{{ route('api.get_city') }}/${id}`)
                .then(({
                    data
                }) => {
                    let result = data.data
                    $("#city_id").empty()
                    let html = `<option value="" selected disabled>== Pilih Kota ==</option>`
                    $.each(result, (id, el) => {
                        if (el.city_id == id_city) {
                            html += `<option value="${el.city_id}" selected>${el.name} - ${el.type}</option>`
                        } else {
                            html += `<option value="${el.city_id}">${el.name} - ${el.type}</option>`
                        }
                    })
                    $("#subdistricts_id").html(`<option value="" selected disabled>== Pilih Kecamatan ==</option>`)
                    $("#city_id").html(html)
                    resolve(result)
                })
                .catch(err => {
                    console.log(err)
                    reject(err)
                })
        })
    }

    const get_subdistrict = (id, id_subdistrict_old) => {
        return new Promise((resolve, reject) => {
            $axios.get(`{{ route('api.get_subdistict') }}/${id}`)
                .then(({
                    data
                }) => {
                    let result = data.data
                    $("#subdistricts_id").empty()
                    let html = `<option value="" selected disabled>== Pilih Kecamatan ==</option>`
                    $.each(result, (id, el) => {
                        if (el.subdistrict_id == id_subdistrict_old) {
                            html += `<option value="${el.subdistrict_id}" selected>${el.subdistrict_name}</option>`
                        } else {
                            html += `<option value="${el.subdistrict_id}">${el.subdistrict_name}</option>`
                        }
                    })
                    $("#subdistricts_id").html(html)
                    resolve(result)
                })
                .catch(err => {
                    console.log(err)
                    reject(err)
                })
        })
    }

    @if(!in_array('nope', $upgrade))
    const upgrade = id => {
        $swal.fire({
                title: 'Yakin ?',
                text: "Ingin upgrade",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                cancelButtonText: 'Tidak',
                confirmButtonText: 'Ya!'
            })
            .then((result) => {
                if (result.isConfirmed) {
                    loading('show', "#btnUpgrade")
                    new Promise((resolve, reject) => {
                        $axios.post(`{{ route('profile.upgrade') }}`)
                            .then(({
                                data
                            }) => {
                                loading('hide', "#btnUpgrade")
                                toastr.success(data.message.head, data.message.body)
                                $("#fieldUpgrade").html(`<p>Request berhasil dikirim ke admin</p>`)
                            })
                            .catch(err => {
                                loading('hide', "#btnUpgrade")
                                throwErr(err)
                            })
                    })
                }
            })
    }
    @endif
</script>
@endsection