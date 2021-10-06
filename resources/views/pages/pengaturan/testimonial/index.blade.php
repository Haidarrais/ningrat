@extends('layouts.dashboard')
@section('content')
    <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <button class="btn btn-primary ml-auto" id="tambah_testimoni">
                            <i class="fas fa-plus"></i>
                            <span>Tambah Konten</span>
                        </button>
                    </div>
                    <div class="card-body overflow-auto">
                        <table class="table" id="table_data">
                                    <thead>
                                        <tr>
                                            <th scope="col">Name</th>
                                            <th scope="col">Actor</th>
                                            <th scope="col">Word</th>
                                            <th scope="col">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($testimonials as $testimonial)
                                        <tr>
                                            <td>{{$testimonial->name}}</td>
                                            <td>{{$testimonial->actor}}</td>
                                            <td><img src="{{ asset('uploads/contents/'.$testimonial->image) }}" alt="{{$testimonial->name}} image" style="max-width: 100px"></td>
                                            <td>
                                                <form action="{{ route('testimonial.destroy', ['testimonial'=>$testimonial->id]) }}" method="POST">
                                                    @method('DELETE')
                                                    @csrf
                                                    <button type="button" class="btn btn-warning" onclick="setIndex({{$testimonial->id}})"><i class="fas fa-edit"></i></button>
                                                    <button type="submit" class="btn btn-danger"><i class="fas fa-trash"></i></button>
                                                </form>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                        </table>
                    </div>
                </div>
            </div>
    </div>
@endsection
@section('modal')
    <div class="modal fade" id="modal_tambah" tabindex="-1" role="dialog" aria-labelledby="modal_tambah" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modal-set-resiLabel">Tambah Testimoni</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('testimonial.store')}}" method="POST" id="form-add-inbox-data" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body row">
                        <div class="form-group col-md-12">
                            <label for="">Nama</label>
                            <input type="text" class="form-control" name="name">
                        </div>
                        <div class="form-group col-md-12">
                            <label for="">Actor</label>
                            <input type="text" class="form-control" name="actor">
                        </div>
                        <div class="form-group col-md-12">
                            <label for="">Word</label>
                            <textarea name="word" class="form-control"></textarea>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="">Image</label>
                            <input type="file" class="form-control" name="image">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="modal fade" id="modal_edit" tabindex="-1" role="dialog" aria-labelledby="modal_edit" aria-hidden="true">
        <div class="modal-dialog" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="modal-set-resiLabel">Edit Data</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <form action="" method="POST" id="form-edit-buy" enctype="multipart/form-data">
                @csrf
                @method("PATCH")
                <div class="modal-body row">
                    <div class="form-group col-md-12">
                        <label for="">Nama</label>
                        <input type="text" class="form-control" name="name" id="name_edit">
                    </div>
                    <div class="form-group col-md-12">
                        <label for="">Actor</label>
                        <input type="text" class="form-control" name="description" id="description_edit">
                    </div>
                    <div class="form-group col-md-12">
                        <label for="">Word</label>
                        <textarea name="word" id="word_edit" class="form-control"></textarea>
                    </div>
                    <div class="form-group col-md-6">
                        <label for="">Image</label>
                        <input type="file" class="form-control" name="image">
                    </div>
                    <div class="form-group col-md-6">
                        <label for="">Recent Image</label>
                        <img src="" id="image_edit" style="max-width: 210px">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>
            </form>
          </div>
        </div>
    </div>
@endsection
@section('js')
    <script>
        $('#tambah_testimoni').on('click', () => {
            $('#modal_tambah').modal('show')
        });
        function setIndex(id) {
                // index = id;
                // console.log(index);
                var url = "{{route('testimonial.edit', ":id")}}";
                url = url.replace(":id", id);
                $.ajax({
                    type: 'GET',
                    url: url,
                    success: function(data) {
                        var imgUrl = "{{asset('uploads/contents/' . "data.data.image")}}";
                        imgUrl = imgUrl.replace("data.data.image", data.data.image);
                        console.log(imgUrl);
                        $('#modal_edit').modal('show')
                        $("#name_edit").val(data.data.name)
                        $("#actor_edit").val(data.data.actor)
                        $("#word_edit").val(data.data.word)
                        $("#image_edit").attr("src", imgUrl)
                        var formAction = "{{route('testimonial.update', ":id")}}";
                        formAction = formAction.replace(':id', id);
                        $("#form-edit-buy").attr("action", formAction);
                    },
                });
            };
    </script>
@endsection
