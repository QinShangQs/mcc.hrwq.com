
@extends('layouts.material')
@section('content')
    <div class="container">
        <div class="block-header">
            <h2><a href="{{route('agency.index')}}">课程类别</a>  -> 编辑课程类别</h2>
        </div>
        <div class="card">
            <div class="card-body card-padding">
                @if (count($errors) > 0)
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form id="post_form" action="{{route('agency.update',['id'=>$agency->id])}}" method="post">
                    {!! csrf_field() !!}
                    <div class="row">
                        <div class="col-sm-3">
                            <div class="form-group fg-line ">
                                <label>类别名</label>
                                <input type="text" value="{{$agency->agency_name}}" name="agency_name"  class="form-control input-sm" >
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group fg-line ">
                                <label>类别介绍</label>
                                <textarea class="form-control" rows="5" placeholder="" name="agency_title">{{$agency->agency_title}}</textarea>
                            </div>
                        </div>
                    </div>
                    <div class="form-group fg-line">
                        <button class="btn bgm-cyan waves-effect" >保存</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
