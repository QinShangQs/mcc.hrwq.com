@extends('layouts.material')
@section('style')
<link href="/vendors/webuploader/webuploader.css" rel="stylesheet">
@endsection
@section('content')
<div class="container">
    <div class="block-header">
        <h2><a href="{{route('ad')}}">广告列表</a>  -> 添加广告</h2>
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

            <form id="post_form" action="{{route('ad.store')}}" method="post">
                {!! csrf_field() !!}
                <input type="hidden" id="domain" value="{{config('qiniu.DOMAIN')}}">
                <input type="hidden" id="uptoken_url" value="{{route('vcourse.qiniu_uptoken')}}">
                <div class="row">
                    <div class="col-sm-3">
                        <div class="form-group fg-line ">
                            <label>名称</label>
                            <input type="text" value="{{old('title')}}"  name="title"    class="form-control input-sm" >
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-2">
                        <div class="form-group fg-line ">
                            <label>显示位置</label>
                            <select class="selectpicker" name="ad_type" id="ad_type">
                                <option value=""  >--请选择类型--</option>
                                @foreach($ad_types as $k=>$v)
                                <option value="{{ $k }}" @if($k==old('ad_type')) selected @endif>{{$v}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                <div class="row" id="image-ad" style="display: none">
                    <div class="col-sm-6">
                        <div class="form-group fg-line ">
                            <label>广告图片 <small class="c-red">(限1张,大小限制2MB)</small></label>
                            <div id="uploader" class="wu-example">
                                <div class="queueList">
                                    <div id="dndArea" class="placeholder">
                                        <div id="filePicker" class="webuploader-container">
                                            <div class="webuploader-pick">点击选择图片</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="statusBar" style="display: none;">
                                    <div class="progress">
                                        <span class="text"></span>
                                        <span class="percentage"></span>
                                    </div>
                                    <div class="btns">
                                        <div id="filePicker2" class="webuploader-container"></div>
                                    </div>
                                    <div class="uploadBtn state-pedding">开始上传</div>
                                </div>
                                <input type="hidden" id="cover_image" name="image_url" value="{{old('image_url')}}" />
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row" id="video-ad" style="display: none">
                    <div class="col-sm-12">
                        <div class="form-group fg-line ">
                            <label>视频上传</label><br />
                            <div id="container" class="m-t-20">
                                <a href="#" id="pickfiles">
                                    <span class="btn btn-success btn-sm  waves-effect ">
                                        <i class="zmdi zmdi-plus zmdi-hc-fw"></i>选择文件
                                    </span>
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12 ">
                        <table class="table table-striped table-hover text-left" style="margin-bottom:40px;">
                            <thead>
                                <tr>
                                    <th class="col-md-6">文件名</th>
                                    <th class="col-md-1">大小</th>
                                    <th class="col-md-5">详细</th>
                                </tr>
                            </thead>
                            <tbody id="fsUploadProgress">
                                @if(old('video_tran'))
                                <tr id="" class="progressContainer" style="opacity: 1;">
                                    <td class="progressName">{{old('video_original')}}
                                        <div class="m-t-20">
                                            <span class="origin-video btn  btn-primary play-btn" data-url="{{config('qiniu.DOMAIN').old('video_original')}}">播放原视频</span>
                                            <span class="origin-video btn  btn-primary play-btn m-l-5" style="" data-url="{{config('qiniu.DOMAIN').old('video_tran')}}">播放转码后视频</span>
                                            <span class="origin-video btn  btn-primary play-btn m-l-5" style="" data-url="{{config('qiniu.DOMAIN').old('video_free')}}">播放试看视频</span>
                                            <span class="origin-video btn  btn-primary delete-btn m-l-5" style="" data-keya="{{old('video_original')}}" data-keyb="{{old('video_tran')}}" data-keyf="{{old('video_free')}}">删除视频</span>
                                            <input type="hidden" name="video_original" value="{{old('video_original')}}">
                                            <input type="hidden" name="video_tran" value="{{old('video_tran')}}">
                                            <input type="hidden" name="video_free" value="{{old('video_free')}}">
                                        </div>
                                    </td>
                                    <td class="progressFileSize"></td>
                                    <td>
                                    </td>
                                </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                    <div style="display:none" id="success" class="col-md-12 m-b-10">
                        <div class="alert-success">
                            队列全部文件处理完毕
                        </div>
                    </div>
                    <div class="modal fade body" id="myModal-video" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                    <h4 class="modal-title" id="myModalLabel">视频播放</h4>
                                </div>
                                <div class="modal-body">
                                    <div class="modal-body-wrapper text-center">
                                        <div id="video-container" style="border:0px solid #999;">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row r_item r_url">
                    <div class="col-sm-3">
                        <div class="form-group fg-line ">
                            <label>链接地址</label>
                            <input type="text" value="{{old('redirect_url')}}"  name="redirect_url" class="form-control input-sm" >
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
@section('script')
<script type="text/javascript" src="/vendors/webuploader/webuploader.min.js"></script>
<script type="text/javascript" src="/vendors/webuploader/carousel_webupload.js"></script>

<script type="text/javascript" src="/qiniu/js/plupload/plupload.full.min.js"></script>
<script type="text/javascript" src="/qiniu/js/plupload/i18n/zh_CN.js"></script>
<script type="text/javascript" src="/qiniu/js/qiniu.js"></script>
<script type="text/javascript" src="/qiniu/js/main.js"></script>
<script type="text/javascript" src="/qiniu/js/ui.js"></script>
<script type="text/javascript">
//收费类别
$(document).ready(function(){
    $("#ad_type").val(1);
    $("#ad_type").change(function () {
        if ($(this).val() == 1) {
            $('#image-ad').show();
            $('#video-ad').hide();
        } else if($(this).val() == 2) {
            $('#image-ad').hide();
            $('#video-ad').show();
        }
    });
    
    $("#ad_type").change();
});

</script>
@endsection