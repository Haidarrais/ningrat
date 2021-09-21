@extends('layouts.main')
@section('content')
    <style>
        ul.timeline {
            list-style-type: none;
            position: relative;
        }
        ul.timeline:before {
            content: ' ';
            background: #d4d9df;
            display: inline-block;
            position: absolute;
            left: 29px;
            width: 2px;
            height: 100%;
            z-index: 400;
        }
        ul.timeline > li {
            margin: 20px 0;
            padding-left: 50px;
        }
        ul.timeline > li:before {
            content: ' ';
            background: white;
            display: inline-block;
            position: absolute;
            border-radius: 50%;
            border: 3px solid #22c0e8;
            left: 20px;
            width: 20px;
            height: 20px;
            z-index: 400;
        }
    </style>
    <div class="breadcrumb-area bg-12 text-center">
        <div class="container">
            <h1>{{$transaction->invoice}} Pengiriman Melalui {{$transaction->shipping}}</h1>
            <nav aria-label="breadcrumb">
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="/">Home</a></li>
                    <li class="breadcrumb-item"><a href="/profile/">Profile</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Lacak</li>
                </ul>
            </nav>
        </div>
    </div>
    <div class="container">
        <div class="card">
            <div class="card-body">
                <a href="{{route('profile.customer')}}" class="btn btn-primary card-title float-right">Back</a>
                <h5 class="card-title">Tracking {{$transaction->invoice}}</h5>
                <br>
                <hr>
                <ul class="timeline">
                    @if ($result['delivered'])
                        <li>
                            <a target="_blank" href="#">{{$result['delivery_status']['status']}} - <small>{{$result['delivery_status']['pod_time']}}</small></a>
                            <a href="#" class="float-right">{{$result['delivery_status']['pod_date']}}</a>
                            <p>Penerima <small>({{$result['delivery_status']['pod_receiver']}})</small></p>
                        </li>
                    @endif
                    @foreach ($result['manifest'] as $key => $manifest )
                        <li>
                            <a target="_blank" href="#">{{$manifest['manifest_description']}} - <small>{{$manifest['manifest_time']}}</small></a>
                            <a href="#" class="float-right">{{$manifest['manifest_date']}}</a>
                            <p>{{$manifest['city_name']}}</p>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection
