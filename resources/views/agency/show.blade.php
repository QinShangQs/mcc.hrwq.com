@extends('layouts.material')
@section('content')
    <div class="container">

        <div class="block-header">
            <h2><a href="{{route('agency.index')}}">课程类别</a> -> 课程类别详情</h2>
        </div>
        <div class="card">
            <div class="card-body card-padding">
                <div class="form-group fg-line ">
                    <label for="exampleInputEmail1">类别ID</label>
                    <input type="text" disabled="disabled" value="{{ $agency->id }}" class="form-control input-sm">
                </div>

                <div class="form-group fg-line">
                    <label for="exampleInputPassword1">类别名</label>
                    <input type="text" disabled="disabled" value="{{ $agency->agency_name }}" class="form-control input-sm">
                </div>

                <div class="form-group fg-line">
                    <label for="exampleInputPassword1">类别介绍</label>
                    <textarea class="form-control" rows="5" placeholder="" name="agency_title" disabled="disabled">{{$agency->agency_title}}</textarea>
                </div>
            </div>
        </div>
    </div>
@endsection
