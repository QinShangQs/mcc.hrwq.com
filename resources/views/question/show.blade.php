@extends('layouts.material')
@section('content')
    <div class="container">

        <div class="block-header">
            <h2><a href="{{route('question.index')}}">问题榜</a> -> 问题详情</h2>
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
                            <label>问题描述</label>
                            <input type="text" value="{{$question->content}}"   class="form-control input-sm"  disabled="disabled">
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-2">
                        <div class="form-group fg-line ">
                            <label>提问人</label>
                            <input type="text" value="{{$question->ask_user->nickname}}"   class="form-control input-sm"  disabled="disabled">
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-2">
                        <div class="form-group fg-line ">
                            <label>被问人</label>
                            <input type="text" value="{{$question->answer_user->nickname}}"   class="form-control input-sm"  disabled="disabled">
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-1">
                        <div class="form-group fg-line ">
                            <label>问题状态</label>
                            <input type="text" value="{{$answer_state[$question->answer_flg]}}"   class="form-control input-sm"  disabled="disabled">
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-4">
                        <div class="form-group fg-line ">
                            <label>回答 （回答时间 {{$question->answer_date}}）</label>

                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group fg-line ">
                            @if($question->answer_url)
                                <button class="btn audio">
                                    播放语音
                                    <audio id="audio_res" src="{{config('qiniu.DOMAIN').$question->answer_url}}"></audio>
                                </button>
                            @else
                                暂无回答
                            @endif
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-12">
                        <div class="form-group fg-line ">
                            <label>其他收听人</label>
                            <input type="text" value="{{$listeners}}"   class="form-control input-sm"  disabled="disabled">
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-1">
                        <div class="form-group fg-line ">
                            <label>收听次数</label>
                            <input type="text" value="{{$question->listener_nums}}"   class="form-control input-sm"  disabled="disabled">
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group fg-line ">
                            <label>限时免费（时间区间）</label>
                            <input type="text" value="开始时间{{$question->free_from}}  结束时间{{$question->free_end}}"   class="form-control input-sm"  disabled="disabled">
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
@endsection

@section('script')
    <script>
        $(function(){
            $('.audio').click(function(){
                var audio = document.getElementById("audio_res");
                audio.play();
            })
        })
    </script>
@endsection
