@extends('layouts.material')
@section('style')
<link href="/vendors/webuploader/webuploader.css" rel="stylesheet">
@endsection
@section('content')
<div class="container">
    <div class="block-header">
        <h2><a href="{{route('carousel')}}">前台管理</a>  -> 爱心大使封面</h2>
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

            <form id="post_form" action="{{route('wechat_push.updateLove')}}" method="post" onsubmit="return check()">
                {!! csrf_field() !!}
                

                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group fg-line ">
                            <label>封面图片 <small class="c-red">(限1张,大小限制2MB,宽高750*1334)</small></label>
                            <div id="uploader" class="wu-example">
                                @if(!empty($instance['base64']))
                                    <img src="{{$instance['base64']}}" width="375"/>
                                @endif
                                                                
                                <div class="queueList">
                                    <div id="dndArea" class="placeholder">
                                        <div id="filePicker" class="webuploader-container">
                                            <div class="webuploader-pick">点击选择图片</div>
                                        </div>
                                    </div>
                                </div>
                                <input type="hidden" id="base64" name="base64" value="{{$instance['base64']}}" />
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-3">
                        <div class="form-group fg-line ">
                            <label>昵称文字颜色</label>
                            <input type="text" value="{{$instance['name_color']}}" id="name_color" name="name_color" class="form-control input-sm" >
                        </div>
                    </div>
                </div>

                <div class="form-group fg-line">
                    <button class="btn bgm-cyan waves-effect"  >保存</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
@section('script')
<script type="text/javascript" src="/vendors/webuploader/webuploader.min.js"></script>
    <script type="text/javascript" src="/vendors/webuploader/carousel_webupload.js"></script>

<script type="text/javascript" charset="utf-8" src="/vendors/Ueditor/ueditor.config.js"></script>
<script type="text/javascript" charset="utf-8" src="/vendors/Ueditor/ueditor.all.min.js"></script>
<script type="text/javascript" charset="utf-8" src="/vendors/Ueditor/lang/zh-cn/zh-cn.js"></script>
<script type="text/javascript">
$(function () {
    UE.getEditor('ueditor', {initialFrameHeight: 300});
});

function check(){
    $('#base64').val($('.imgWrap img').eq(0).attr('src'));
    return true;
}

</script>
@endsection