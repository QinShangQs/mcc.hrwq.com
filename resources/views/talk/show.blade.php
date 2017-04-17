@extends('layouts.material')
@section('content')
    <div class="container">

        <div class="block-header">
            <h2><a href="{{route('question.index')}}">互助榜</a> -> 帖子详情</h2>
        </div>

        <div class="card">
            <div class="card-body card-padding">
                <div class="row">
                    <div class="col-sm-4">
                        <div class="form-group fg-line ">
                            <label>标签</label>
                            <br/>
                            @foreach ($question->tags as $tag)
                                <button class="btn btn-primary btn-sm  waves-effect" > {{ $tag->title }}</button>
                            @endforeach
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-9">
                        <div class="form-group fg-line ">
                            <label>帖子标题</label>
                            <input type="text" value="{{$question->title}}"   class="form-control input-sm"  disabled="disabled">
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-9">
                        <div class="form-group fg-line ">
                            <label>内容</label>
                            <textarea disabled="disabled" class="form-control" rows="5" placeholder="" name="agency_title">{{$question->content}}</textarea>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-2">
                        <div class="form-group fg-line ">
                            <label>提出人</label>
                            <input type="text" value="{{$question->ask_user->nickname}}"   class="form-control input-sm"  disabled="disabled">
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-2">
                        <div class="form-group fg-line ">
                            <label>提问时间</label>
                            <input type="text" value="{{$question->created_at}}"   class="form-control input-sm"  disabled="disabled">
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-1">
                        <div class="form-group fg-line ">
                            <label>查看数</label>
                            <input type="text" value="{{$question->view}}"   class="form-control input-sm"  disabled="disabled">
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-1">
                        <div class="form-group fg-line ">
                            <label>评论数</label>
                            <a class="row " href="{{route('talk.comments',['id'=>$question->id])}}">
                                <button class="btn bgm-red waves-effect">{{$question->comments->count()}}<i class="zmdi zmdi-redo zmdi-hc-fw"></i></button>
                            </a>
                        </div>
                    </div>
                </div>

            </div>
        </div>

    </div>
@endsection
