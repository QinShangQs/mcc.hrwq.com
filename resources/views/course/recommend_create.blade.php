@extends('layouts.material')
@section('content')
    <div class="container">
        <div class="block-header">
            <h2><a href="{{route('course.recommend')}}">推荐管理</a>  -> 添加推荐课程</h2>
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

                <form id="post_form"  action="{{route('course.recommend_store')}}" method="post">
                    {!! csrf_field() !!}
                    <div class="form-group fg-line ">
                        <label for="exampleInputEmail1">课程标题</label>
                        <div class="select"  style="width: 25%;">
                            <select name="id[]" class="selectpicker" multiple="multiple">
                                <option value="">请选择推荐的课程</option>
                                @foreach($courses as $course)
                                    <option value="{{$course->id}}">{{$course->title}}</option>
                                @endforeach
                            </select>
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

