@extends('layouts.dashboard')

@section('content')
<div class="section-body">
    <div class="card">
        <div class="card-header">
            <h4>Presentase pembagian hasil untuk Reseller</h4>
        </div>
        <div class="card-body">
            @if(session()->has('success'))
                <div class="alert alert-success alert-dismissible">
                    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                    <strong>Success!</strong> {{session()->get('success')}}
                </div>
            @endif
            <form action="{{ route('royalty.update', ['royalty'=>1]) }}" method="POST">
                @method('PATCH')
                @csrf
                <div class="form-group col-md-6 col-12">
                    <label>Presentase</label>
                    <input class="form-control" type="number" name="royalty" value="{{$royalty['royalty']}}">
                </div>
                <button type="submit" class="btn btn-primary">Submit</button>
            </form>
        </div>
    </div>
</div>
@endsection
