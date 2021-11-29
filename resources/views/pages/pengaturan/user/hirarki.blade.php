@extends('layouts.dashboard')

@section('css')
<style>
    *,
    *:before,
    *:after {
        -webkit-box-sizing: border-box;
        -moz-box-sizing: border-box;
        box-sizing: border-box;
    }

    #wrapper {
        position: relative;
    }

    .branch {
        position: relative;
        margin-left: 250px;
    }

    .branch:before {
        content: "";
        width: 50px;
        border-top: 5px solid #eee9dc;
        border-radius: 2px;
        position: absolute;
        left: -100px;
        top: 50%;
        margin-top: 1px;
    }

    .entry {
        position: relative;
        min-height: 60px;
    }

    .entry:before {
        content: "";
        height: 100%;
        border-left: 5px solid #eee9dc;
        border-radius: 2px;
        position: absolute;
        left: -50px;
    }

    .entry:after {
        content: "";
        width: 50px;
        border-top: 5px solid #eee9dc;
        border-radius: 2px;
        position: absolute;
        left: -50px;
        top: 50%;
        margin-top: 1px;
    }

    .entry:first-child:before {
        width: 10px;
        height: 50%;
        top: 50%;
        margin-top: 2px;
        border-radius: 10px 0 0 0;
    }

    .entry:first-child:after {
        height: 10px;
        border-radius: 10px 0 0 0;
    }

    .entry:last-child:before {
        width: 10px;
        height: 50%;
        border-radius: 0 0 0 10px;
    }

    .entry:last-child:after {
        height: 10px;
        border-top: none;
        border-bottom: 5px solid #eee9dc;
        border-radius: 2px;
        border-radius: 0 0 0 10px;
        margin-top: -9px;
    }

    .entry.sole:before {
        display: none;
    }

    .entry.sole:after {
        width: 50px;
        height: 0;
        margin-top: 1px;
        border-radius: 0;
    }

    .label {
        display: block;
        min-width: 150px;
        padding: 5px 10px;
        line-height: 20px;
        text-align: center;
        border: 2px solid #eee9dc;
        position: absolute;
        left: 0;
        top: 50%;
        margin-top: -15px;
    }

    /* .tf-custom .tf-nc {
        height: 1em;
        width: 1em;
        background-color: dodgerblue;
        border-color: dodgerblue;
        padding: 0;
        border-radius: 50%;
    } */

    .tf-nc{
        width: 160px;
    }
    /* make the horizontal and vertical connectors thick and change their color */
    .tf-tree .tf-nc,
    .tf-tree .tf-node-content {
        background-color: #6777ef;
        color: white;
        border: 2px solid #eee9dc;
        border-radius: 15px;
    }

    .tf-tree .tf-nc,
    .tf-tree .tf-node-content:hover {
        text-decoration: none;
    }

    .tf-tree .tf-nc p {
        text-decoration: none;
        color: white;
        display: flex;
        flex-direction: column;
        align-items: center;
    }

    .tf-tree .tf-nc p:hover {
        color: white;
        text-decoration: none;
    }

    .tf-custom .tf-nc:before,
    .tf-custom .tf-nc:after {
        border-left-color: dodgerblue;
        border-left-width: 2px;
    }

    .tf-custom li li:before {
        border-top-color: dodgerblue;
        border-top-width: 2px;
    }
</style>
@endsection

@section('content')
<div class="section-body">
    <div class="card">
        @role('superadmin')
        <div class="card header">
            <select name="user" id="user" class="form-control select2" autocomplete="off">
                @php
                $users = User::all();
                @endphp
                @foreach ($users as $value)
                <option value="{{ $value->api_token }}" {{ $value->api_token == $user->api_token ? 'selected' : '' }}>{{ $value->name }}</option>
                @endforeach
            </select>
        </div>
        @endrole
        <div class="card-body">
            <div id="wrapper">
                <div class="tf-tree d-flex justify-content-center">
                    <ul class="w-100">
                        <li>
                            <span class="tf-nc "><a href="{{ route('users.hirarki', base64_encode($user->api_token)) }}">
                                    <p class="">
                                        {{ $user->name }}
                                        <br><small>{{ $user->getRoleNames()->first() }}</small>
                                    </p>
                                </a></span>
                            {{-- Anak secara langsung ? Generasi 1 --}}
                            @if ($countAnak = $user->hirarki->count())
                            <ul>
                                @foreach($user->hirarki as $child1)
                                <li>
                                    <span class="tf-nc"><a href="{{ route('users.hirarki', base64_encode($child1->api_token)) }}">
                                            <p class="">
                                                {{ $child1->name }}
                                                <br><small>{{ $child1->getRoleNames()->first() }}</small>
                                            </p>
                                        </a>
                                </span>
                                @if ($coutnGen2 = $child1->hirarki->count())
                                <ul>
                                    @foreach($child1->hirarki as $child2)
                                    <li>
                                        <span class="tf-nc"><a href="{{ route('users.hirarki', base64_encode($child2->api_token)) }}">
                                                <p class="">
                                                    {{ $child2->name }}
                                                    <br><small>{{ $child2->getRoleNames()->first() }}</small>
                                                </p>
                                            </a>
                                        </span></li>
                                    @endforeach
                                </ul>
                                @endif
                                </li>
                                @endforeach
                            </ul>
                            @endif
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('js')
<script>
    let toggler = document.getElementsByClassName("caret")
    let i

    for (i = 0; i < toggler.length; i++) {
        toggler[i].addEventListener("click", function() {
            this.parentElement.querySelector(".nested").classList.toggle("active")
            this.classList.toggle("caret-down")
        })
    }

    $("#user").change(e => {
        let val = $(e.currentTarget).val()
        let url = `{{ route('users.hirarki', ['id' => ':api_token']) }}`
        url = url.replace(':api_token', btoa(val))
        console.log(url)
        window.location.replace(`${url}`)
    })
</script>
@endsection