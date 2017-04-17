@extends('layouts.material')
@section('content')
    <div class="container">
        <div class="block-header">
            <h2><a href="{{route('article')}}">文案列表</a>  -> 文案详情</h2>
        </div>
        <div class="card">
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