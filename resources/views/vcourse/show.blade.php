@extends('layouts.material')
@section('content')
    <link href="/qiniu/js/videojs/video-js.min.css" rel="stylesheet">
    <script src="/qiniu/js/videojs/video.min.js"></script>
    <div class="container">
        <div class="block-header">
            <h2><a href="{{route('vcourse.index')}}">视频课程</a>  -> 视频课程详情</h2>
        </div>
        <div class="card">
            <div class="card-body card-padding">
                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group fg-line ">
                            <label>课程名称</label>
                            <input type="text" value="{{$vcourse->title}}" name="title"  class="form-control input-sm" disabled="disabled">
                        </div>
                    </div>
                </div>
                <div class="row">
                        <div class="col-sm-3">
                            <div class="form-group fg-line ">
                                <label for="exampleInputEmail1">图片</label>
                                @if($vcourse->cover)
                                    <span  id="preview_image"  class="row"><img id="img0" src="{{ asset($vcourse->cover) }}"></span>
                                @else
                                    <span  id="preview_image"  class="row">暂未上传图片</span>
                                @endif
                            </div>
                        </div>
                    </div>
                <div class="row">
                    <div class="col-sm-3">
                        <div class="form-group fg-line ">
                            <label>课程类别</label>
                            <select class="selectpicker" size="10"  name="agency_id" disabled="disabled">
                                @foreach ($agencyArr as $key=>$item)
                                    <option value="{{$key}}" @if($vcourse->agency_id==$key) selected @endif>{{$item}}</option>
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
                                    <input type="radio" name="type" id="free" value="1" @if($vcourse->type=='1'||empty($vcourse->type)) checked @endif disabled="disabled"><i class="input-helper"></i>  
                                    免费
                                </label>
                                <label class="radio radio-inline">
                                    <input type="radio" name="type" value="2" @if($vcourse->type=='2') checked @endif disabled="disabled"><i class="input-helper"></i>  
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
                            <input type="text" value="{{$vcourse->price}}" name="price"  class="form-control input-sm" disabled="disabled">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-3">
                        <div class="form-group fg-line ">
                            <label>讲师</label>
                            <input type="text" value="{{$vcourse->teacher}}" name="teacher"  class="form-control input-sm" disabled="disabled">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group fg-line ">
                            <label>讲师简介</label>
                            <textarea class="form-control" rows="5" placeholder="" name="teacher_intr" disabled="disabled">{{$vcourse->teacher_intr}}</textarea>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-2">
                        <div class="form-group fg-line ">
                            <label>当前课时</label>
                            <input type="text" value="{{$vcourse->current_class}}" name="current_class"  class="form-control input-sm" disabled="disabled">
                        </div>
                    </div>
                    <div class="col-sm-2">
                        <div class="form-group fg-line ">
                            <label>总课时</label>
                            <input type="text" value="{{$vcourse->total_class}}" name="total_class"  class="form-control input-sm" disabled="disabled">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group fg-line ">
                            <label>适合对象</label>
                            <textarea class="form-control" rows="5" placeholder="" name="suitable" disabled="disabled">{{$vcourse->suitable}}</textarea>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group fg-line ">
                            <label>课程目标</label>
                            <textarea class="form-control" rows="5" placeholder="" name="vcourse_obj" disabled="disabled">{{$vcourse->vcourse_obj}}</textarea>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group fg-line ">
                            <label>课程简介</label>
                            <textarea class="form-control" rows="5" placeholder="" name="vcourse_des" disabled="disabled">{{$vcourse->vcourse_des}}</textarea>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group fg-line ">
                            <label>课程作业</label>
                            <textarea class="form-control" rows="5" placeholder="" name="work" disabled="disabled">{{$vcourse->work}}</textarea>
                        </div>
                    </div>
                </div>
                @if($vcourse->video_tran)
                <div class="row">
                <div class="col-sm-12">
                        <div class="form-group fg-line ">
                            <label>课程视频</label>
                        </div>
                    </div>
                    <div class="col-md-12 ">
                        <table class="table table-striped table-hover text-left" style="margin-bottom:40px;">
                            <tbody id="fsUploadProgress">
                                <tr id="" class="progressContainer" style="opacity: 1;">
                                  <td class="progressName">{{$vcourse->video_original}} (转码后视频：{{config('qiniu.DOMAIN').$vcourse->video_tran}})
                                    <div class="m-t-20">
                                      <span class="origin-video btn  btn-primary play-btn" data-url="{{config('qiniu.DOMAIN').$vcourse->video_original}}">播放原视频</span>
                                      <span class="origin-video btn  btn-primary play-btn m-l-5" style="" data-url="{{config('qiniu.DOMAIN').$vcourse->video_tran}}">播放转码后视频</span>
                                      <span class="origin-video btn  btn-primary play-btn m-l-5" style="" data-url="{{config('qiniu.DOMAIN').$vcourse->video_free}}">播放试看视频</span></div>
                                  </td>
                                </tr>
                            </tbody>
                        </table>
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
                @endif
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script type="text/javascript">
            function initPlayer(vLink) {

        if ($("#video-embed").length) {
            return;
        }

        var vType = function() {
            $.ajaxSetup({
                headers: ''
            });
            var type = '';
            $.ajax({
                url: vLink + "?stat",
                async: false
            }).done(function(info) {
                type = info.mimeType;
                if (type == 'application/x-mpegurl') {
                    type = 'application/x-mpegURL';
                }
            });

            return type;
        };

        var videoEnd = function(){
          console.log('ended');
        };
        var videoPlay = function(){
          console.log('play');
        };

        var player = $('<video id="video-embed" class="video-js vjs-default-skin vjs-big-play-centered" style="width: 100%;height: 500px;"></video>');
        $('#video-container').empty();
        $('#video-container').append(player);

        var poster = vLink + '?vframe/jpg/offset/2';
        videojs('video-embed', {
            "width": "100%",
            "height": "500px",
            "controls": true,
            "autoplay": false,
            "preload": "auto",
            "poster": poster
        }, function() {
            this.src({
                type: vType(),
                src: vLink
            });
        }).on("ended", videoEnd).on("play", videoPlay);
    }

    function disposePlayer() {
        if ($("#video-embed").length) {
            $('#video-container').empty();
            // _V_('video-embed').dispose();
            var player = videojs('video-embed');
            player.dispose();
        }
    }


    $('#myModal-video').on('hidden.bs.modal', function() {
        disposePlayer();
    });

    $('tbody').on('click', '.play-btn', function() {
        $('#myModal-video').modal();
        var url = $(this).data('url');
        initPlayer(url);
    });
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
        })
    </script>
@endsection
