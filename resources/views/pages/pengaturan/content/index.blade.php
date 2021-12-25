@extends('layouts.dashboard')
@section('content')
    <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <!-- Nav tabs -->
                        <ul class="nav nav-tabs">
                            <li class="nav-item">
                                <a class="nav-link active" data-toggle="tab" href="#carousel">Carousel</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-toggle="tab" href="#banner">Banner</a>
                            </li>
                        </ul>
                        <button class="btn btn-primary ml-auto" id="tambah_konten">
                            <i class="fas fa-plus"></i>
                            <span>Tambah Konten</span>
                        </button>
                    </div>
                    <div class="card-body overflow-auto">
                        <!-- Tab panes -->
                        <div class="tab-content">
                            <div id="carousel" class="container tab-pane active"><br>
                                <table class="table" id="table_data">
                                    <thead>
                                        <tr>
                                            <th scope="col">Name</th>
                                            <th scope="col">Description</th>
                                            <th scope="col">Image</th>
                                            <th scope="col">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($carousel_items as $carousel)
                                        <tr>
                                            <td>{{$carousel->name}}</td>
                                            <td>{{$carousel->description}}</td>
                                            <td><img src="{{ asset('uploads/contents/'.$carousel->image) }}" alt="{{$carousel->name}} image" style="max-width: 100px"></td>
                                            <td>
                                                <form action="{{ route('content.destroy', ['content'=>$carousel->id]) }}" method="POST">
                                                    @method('DELETE')
                                                    @csrf
                                                    <button type="button" class="btn btn-warning" onclick="setIndex({{$carousel->id}})"><i class="fas fa-edit"></i></button>
                                                    <button type="submit" class="btn btn-danger"><i class="fas fa-trash"></i></button>
                                                </form>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <div id="banner" class="container tab-pane fade"><br>
                                <table class="table" id="table_data">
                                    <thead>
                                        <tr>
                                            <th scope="col">Name</th>
                                            <th scope="col">Description</th>
                                            <th scope="col">Image</th>
                                            <th scope="col">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($banner_items as $banner)
                                        <tr>
                                            <td>{{$banner->name}}</td>
                                            <td>{{$banner->description}}</td>
                                            <td><img src="{{ asset('uploads/contents/'.$banner->image) }}" alt="{{$banner->name}} image" style="max-width: 100px"></td>
                                            <td>
                                                <form action="{{ route('content.destroy', ['content'=>$banner->id]) }}" method="POST">
                                                    @method('DELETE')
                                                    @csrf
                                                    <button type="button" class="btn btn-warning" onclick="setIndex({{$banner->id}})"><i class="fas fa-edit"></i></button>
                                                    <button type="button" class="btn btn-success" onclick="setIndexArticle({{$banner->id}})"><i class="fas fa-newspaper"></i></button>
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
            </div>
    </div>
@endsection
@section('modal')
    <div class="modal fade" id="modal_tambah" tabindex="-1" role="dialog" aria-labelledby="modal_tambah" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modal-set-resiLabel">Tambah Konten</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('content.store')}}" method="POST" id="form-add-inbox-data" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body row">
                        <div class="form-group col-md-12">
                            <label for="">Nama Konten</label>
                            <input type="text" class="form-control" name="name">
                        </div>
                        <div class="form-group col-md-12">
                            <label for="">Deskripsi</label>
                            <input type="text" class="form-control" name="description">
                        </div>
                        <div class="form-group col-md-6">
                            <label for="">Gambar</label>
                            <input type="file" class="form-control" name="image">
                        </div>
                        <div class="form-group col-md-6">
                            <label for="">Tipe</label>
                            <select name="content_type" class="form-control">
                                <option value="1">Carousel</option>
                                <option value="2">Banner</option>
                            </select>
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
                        <label for="">Nama Konten</label>
                        <input type="text" class="form-control" name="name" id="name_edit">
                    </div>
                    <div class="form-group col-md-12">
                        <label for="">Deskripsi</label>
                        <input type="text" class="form-control" name="description" id="description_edit">
                    </div>
                    <div class="form-group col-md-6">
                        <label for="">Gambar</label>
                        <input type="file" class="form-control" name="image">
                    </div>
                    <div class="form-group col-md-6">
                        <label for="">Tipe</label>
                        <select name="content_type" class="form-control" id="content_type_edit">
                            <option value="1">Carousel</option>
                            <option value="2">Banner</option>
                        </select>
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
    <div class="modal fade" id="modal_edit_article" tabindex="-1" role="dialog" aria-labelledby="modal_edit_article" aria-hidden="true">
        <div class="modal-dialog" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="modal-set-resiLabel">Article</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <form action="" method="POST" id="form-edit-article" enctype="multipart/form-data">
                @csrf
                @method("POST")
                <input type="number" name="banner_id" id="banner_id" hidden>
                <div class="modal-body row">
                    <label for="article">Article</label>
                    <textarea name="article" id="article" rows="40" class="form-control"></textarea>
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
    <script src="https://cdn.ckeditor.com/4.17.1/standard/ckeditor.js"></script>
    <script>
            CKEDITOR.replace( 'article' );
    </script>
    <script>
        $('#tambah_konten').on('click', () => {
            $('#modal_tambah').modal('show')
        });
        function setIndex(id) {
                // index = id;
                // console.log(index);
                var url = "{{route('content.edit', ":id")}}";
                url = url.replace(":id", id);
                $.ajax({
                    type: 'GET',
                    url: url,
                    success: function(data) {
                        console.log(data);
                        $('#modal_edit').modal('show')
                        $("#name_edit").val(data.data.name)
                        $("#description_edit").val(data.data.description)
                        $("#content_type_edit").val(data.data.content_type)
                        var formAction = "{{route('content.update', ":id")}}";
                        formAction = formAction.replace(':id', id);
                        $("#form-edit-buy").attr("action", formAction);
                    },
                });
            };
            function setIndexArticle(id) {
                // index = id;
                // console.log(index);
                var url = "{{route('article.edit', ":id")}}";
                url = url.replace(":id", id);
                $.ajax({
                    type: 'GET',
                    url: url,
                    success: function(data) {
                        if (data && data.data && data.data.articles) {
                            console.log(data.data.articles);
                            $('#modal_edit_article').modal('show')
                            $("#article").html(data.data.articles)
                        }
                            $("#banner_id").val(id)
                        var formAction = "{{route('article.store', ":id")}}";
                        formAction = formAction.replace(':id', id);
                        $("#form-edit-article").attr("action", formAction);
                    },
                });
            };
    </script>
@endsection
