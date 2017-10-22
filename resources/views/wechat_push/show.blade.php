@extends('layouts.material')
@section('content')
    <div class="container">

        <div class="block-header">
            <h2><a href="{{route('agency.index')}}">用户管理</a> -> 留言详情</h2>
        </div>
        <div class="card">
            <div class="card-body card-padding">
                <div class="form-group fg-line ">
                    <label for="exampleInputEmail1">ID</label>
                    <input type="text" disabled="disabled" value="{{ $word->id }}" class="form-control input-sm">
                </div>
                <div class="form-group fg-line ">
                    <label for="exampleInputEmail1">用户</label>
                    <input type="text" disabled="disabled" value="{{ $word_user->nickname }} / {{ $word_user->mobile }}" class="form-control input-sm">
                </div>
                <div class="form-group fg-line">
                    <label for="exampleInputPassword1">内容</label>
                    <input type="text" disabled="disabled" value="{{ $word->content }}" class="form-control input-sm">
                </div>

                <div class="form-group fg-line">
                    <label for="exampleInputPassword1">发表时间</label>
                    <textarea class="form-control" rows="5" disabled="disabled">{{$word->created_at}}</textarea>
                </div>
            </div>
        </div>
    </div>
@endsection
