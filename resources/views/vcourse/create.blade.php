@extends('layouts.material')
@section('style')
    <link href="/vendors/webuploader/webuploader.css" rel="stylesheet">
@endsection
@section('content')
    <link href="/qiniu/js/videojs/video-js.min.css" rel="stylesheet">
    <script src="/qiniu/js/videojs/video.min.js"></script>
    <div class="container">
        <div class="block-header">
            <h2><a href="{{route('vcourse.index')}}">视频课程</a>  -> 添加视频课程</h2>
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

                <form id="post_form" action="{{route('vcourse.store')}}" method="post">
                    {!! csrf_field() !!}
                    <input type="hidden" id="domain" value="{{config('qiniu.DOMAIN')}}">
                    <input type="hidden" id="uptoken_url" value="{{route('vcourse.qiniu_uptoken')}}">
                    <input type="hidden" id="delete_url" value="{{route('vcourse.qiniu_delete')}}">
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group fg-line ">
                                <label>课程名称 <small class="c-red">(15个汉字字以内)</small></label>
                                <input type="text" value="{{old('title')}}" name="title"  class="form-control input-sm" >
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-3">
                            <div class="form-group fg-line ">
                                <label for="exampleInputEmail1">图片</label>
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
                                    <input type="hidden" id="cover_image" name="cover" value="" />
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-3">
                            <div class="form-group fg-line ">
                                <label>课程类别</label>
                                <select class="selectpicker" size="10"  name="agency_id">
                                    @foreach ($agencyArr as $key=>$item)
                                        <option value="{{$key}}" @if(old('agency_id')==$key) selected @endif>{{$item}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-2">
                            <div class="form-group fg-line ">
                                <label>收费类别</label>
                                <div class="m-t-10">
                                    <label class="radio radio-inline">
                                        <input type="radio" name="type" id="free" value="1" @if(old('type')=='1'||empty(old('type'))) checked @endif><i class="input-helper"></i>  
                                        免费
                                    </label>
                                    <label class="radio radio-inline">
                                        <input type="radio" name="type" value="2" @if(old('type')=='2') checked @endif><i class="input-helper"></i>  
                                        收费
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row" id="price">
                        <div class="col-sm-2">
                            <div class="form-group fg-line ">
                                <label>价格</label>
                                <input type="text" value="{{old('price')}}" name="price"  class="form-control input-sm" >
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-3">
                            <div class="form-group fg-line ">
                                <label>讲师</label>
                                <input type="text" value="{{old('teacher')}}" name="teacher"  class="form-control input-sm" >
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group fg-line ">
                                <label>讲师简介</label>
                                <textarea class="form-control" rows="5" placeholder="" name="teacher_intr">{{old('teacher_intr')}}</textarea>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-2">
                            <div class="form-group fg-line ">
                                <label>当前课时</label>
                                <input type="text" value="{{old('current_class')}}" name="current_class"  class="form-control input-sm" >
                            </div>
                        </div>
                        <div class="col-sm-2">
                            <div class="form-group fg-line ">
                                <label>总课时</label>
                                <input type="text" value="{{old('total_class')}}" name="total_class"  class="form-control input-sm" >
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group fg-line ">
                                <label>适合对象</label>
                                <textarea class="form-control" rows="5" placeholder="" name="suitable">{{old('suitable')}}</textarea>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group fg-line ">
                                <label>课程目标</label>
                                <textarea class="form-control" rows="5" placeholder="" name="vcourse_obj">{{old('vcourse_obj')}}</textarea>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group fg-line ">
                                <label>课程简介</label>
                                <textarea class="form-control" rows="5" placeholder="" name="vcourse_des">{{old('vcourse_des')}}</textarea>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group fg-line ">
                                <label>课程作业</label>
                                <textarea class="form-control" rows="5" placeholder="" name="work">{{old('work')}}</textarea>
                            </div>
                        </div>
                    </div>
                    <div class="row">
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
                    <div class="row">
                        <div class="col-sm-3">
                            <div class="form-group fg-line ">
                                <label>排序值<small class="c-red">(数字越大越靠前)</small></label>
                                <input type="text" value="{{old('sort')}}" name="sort" placeholder="" class="form-control input-sm" >
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
<script type="text/javascript" src="/vendors/webuploader/vcourse_webupload.js"></script>
<script type="text/javascript" src="/qiniu/js/plupload/plupload.full.min.js"></script>
<script type="text/javascript" src="/qiniu/js/plupload/i18n/zh_CN.js"></script>
<script type="text/javascript" src="/qiniu/js/qiniu.js"></script>
<script type="text/javascript" src="/qiniu/js/main.js"></script>
<script type="text/javascript" src="/qiniu/js/ui.js"></script>
    <script type="text/javascript">
        $(function(){
            if ($("input[name='type']:checked").val()==1) {
               $('#price').hide();
            } else if ($("input[name='type']:checked").val()==2) {
               $('#price').show();
            }
            //收费类别
            $("input[name='type']").change(function(){
               if ($(this).val()==1) {
                  $('#price').hide();
               } else {
                  $('#price').show();
               }
            });

            $('#post_form').submit(function(){
                if ($('input[name="video_original"]').val()&&$('input[name="video_tran"]').val()=='') {
                    swal("视频正在转码，请耐心等待！");
                    return false;
                };
            });
        })
    </script>
@endsection
