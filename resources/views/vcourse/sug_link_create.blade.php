@extends('layouts.material')
@section('content')
    <div class="container">
        <div class="block-header">
            <h2><a href="{{route('hot_search')}}">视频推荐链接</a></h2>
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

                <form id="post_form" action="{{route('vcourse.sug_link_create')}}" method="post">
                    {!! csrf_field() !!}
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group fg-line ">
                                <label>直播入口</label>
                                <input type="text" value="{{$telecast}}" name="telecast"  class="form-control input-lg" >
                            </div>
                            <div class="form-group fg-line ">
                                <label>视频预告</label>
                                <input type="text" value="{{$foreshow}}" name="foreshow"  class="form-control input-lg	" >
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
