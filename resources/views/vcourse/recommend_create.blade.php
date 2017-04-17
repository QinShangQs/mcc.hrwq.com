@extends('layouts.material')
@section('content')
    <div class="container">
        <div class="block-header">
            <h2><a href="{{route('vcourse.recommend')}}">推荐管理</a>  -> 添加推荐课程</h2>
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
                <form id="post_form" action="{{route('vcourse.recommend_store')}}" method="post">
                    {!! csrf_field() !!}
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group fg-line ">
                                <label>课程标题</label>
                                <select class="selectpicker" size="10" multiple="multiple" name="vcourse_id[]">
                                    <option value="">--请选择推荐的课程--</option>
                                    @foreach ($vcourses as $key=>$vcourse)
                                         <option value="{{$vcourse->id}}">{{$vcourse->title}}</option>
                                    @endforeach
                                </select>
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

