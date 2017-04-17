@extends('layouts.material')
@section('content')
    <div class="container">
        <div class="block-header">
            <h2><a href="{{route('course.index')}}">信息维护</a> -> 产品详情</h2>
        </div>

        <div class="card">
            <div class="card-body card-padding">
                <div class="form-group fg-line ">
                        <label for="exampleInputEmail1">产品名称</label>
                        <input type="text" value="{{ $opo->title }}" name="title"  class="form-control input-sm">
                    </div>

                    <div class="form-group fg-line ">
                        <label for="exampleInputEmail1">价格</label>
                        <input type="text" value="{{ $opo->price }}" name="price"  class="form-control input-sm">
                    </div>

                    <div class="form-group fg-line ">
                        <label for="exampleInputEmail1">项目介绍</label>
                        <input type="text" value="{{ $opo->project_intr }}" name="project_intr"  class="form-control input-sm">
                    </div>

                    <div class="form-group fg-line ">
                        <label for="exampleInputEmail1">图片</label>
                        <div class="row">
                            <img src="{{ asset($opo->picture) }}">
                        </div>
                    </div>

            </div>
        </div>

    </div>
@endsection
