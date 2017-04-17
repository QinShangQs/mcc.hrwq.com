@extends('layouts.material')
@section('content')
    <div class="container">
        <div class="block-header">
            <h2>首页 -> 文章详情</h2>
        </div>
        <div class="card">
            <div class="card-body card-padding" >
                <a href="{{route('article')}}"><button class="btn btn-success btn-sm m-t-10  waves-effect "><i class="zmdi zmdi-arrow-left zmdi-hc-fw"></i>返回列表</button></a>
            </div>
            <div class="card-body card-padding">
                <div class="form-group">
                    <textarea  id="ueditor" name="content" rows="" cols="" style="">{{$data->content}}</textarea>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script type="text/javascript" charset="utf-8" src="/vendors/Ueditor/ueditor.config.js"></script>
    <script type="text/javascript" charset="utf-8" src="/vendors/Ueditor/ueditor.all.min.js"> </script>
    <script type="text/javascript" charset="utf-8" src="/vendors/Ueditor/lang/zh-cn/zh-cn.js"></script>
    <script type="text/javascript">
        $(function(){
            UE.getEditor('ueditor',{initialFrameHeight:300});
        })
    </script>
@endsection