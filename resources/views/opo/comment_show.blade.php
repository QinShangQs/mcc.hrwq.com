@extends('layouts.material')
@section('content')
    <div class="container">
        <div class="block-header">
            <h2><a href="{{route('opo.comment')}}">评论管理</a> -> 评论详情</h2>
        </div>

        <div class="card">
            <div class="card-body card-padding">
                <div class="form-group fg-line ">
                    <label for="exampleInputEmail1">评论内容</label>
                    <input type="text" disabled="disabled" value="{{ $opo_comment->content }}" name="content"  class="form-control input-sm">
                </div>

                <div class="form-group fg-line ">
                    <label for="exampleInputEmail1">课程标题</label>
                    <input type="text" disabled="disabled" value="{{ $arrOpos[$opo_comment->opo_id] }}" name="title"  class="form-control input-sm">
                </div>

                <div class="form-group fg-line ">
                    <label for="exampleInputEmail1">评论人</label>
                    <input type="text" disabled="disabled" value="{{ $arrUsers[$opo_comment->user_id] }}" name="title"  class="form-control input-sm">
                </div>

                <div class="form-group fg-line ">
                    <label for="exampleInputEmail1">点赞数</label>
                    <input type="text" disabled="disabled" value="{{$opo_comment->likes}}" name="created_at"  class="form-control input-sm">
                </div>

                <div class="form-group fg-line ">
                    <label for="exampleInputEmail1">评论时间</label>
                    <input type="text" disabled="disabled" value="{{ $opo_comment->created_at }}" name="created_at"  class="form-control input-sm">
                </div>

            </div>
        </div>

    </div>
@endsection
