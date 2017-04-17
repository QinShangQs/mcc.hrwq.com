@extends('layouts.material')
@section('content')
    <div class="container">
        <div class="block-header">
            <h2><a href="{{route('hot_search')}}">关键词列表</a>  -> 添加关键词</h2>
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

                <form id="post_form" action="{{route('hot_search.store')}}" method="post">
                    {!! csrf_field() !!}
                    <div class="row">
                        <div class="col-sm-2">
                            <div class="form-group fg-line ">
                                <label>名称</label>
                                <input type="text" value="{{old('title')}}" name="title"  class="form-control input-sm" >
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-2">
                            <div class="form-group fg-line ">
                                <label>所属类别</label>
                                <select class="form-control" name="type" placeholder="所属类型">
                                    <option value="">--请选择--</option>
                                    @foreach ($type as $k=>$v)
                                        <option value="{{$k}}" @if(old('type')==$k) selected @endif>{{$v}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-2">
                            <div class="form-group fg-line ">
                                <label>排序 <small class="c-red">(数字越大越靠前)</small></label>
                                <input type="text" value="{{old('sort')}}" name="sort"  class="form-control input-sm" >
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
