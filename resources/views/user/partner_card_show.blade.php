@extends('layouts.material')
@section('content')
<link href="/qiniu/js/videojs/video-js.min.css" rel="stylesheet">
<script src="/qiniu/js/videojs/video.min.js"></script>
<div class="container">
    <div class="block-header">
        <h2>合伙人卡片管理 -> 卡片详情</h2>
    </div>

    <div class="card">
        <div class="card-body card-padding">
            <div class="row">
                <div class="col-sm-10">
                    <div class="form-group fg-line ">
                        <label>封面图</label>
                        <span class="row"><img id="img0" src="{{ $user->cover_url }}" style="width: 300px;"></span>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-10">
                    <div class="form-group fg-line ">
                        <label for="exampleInputEmail1">昵称</label>
                        <input type="text" disabled="disabled" value="{{ $user->user->nickname }}" class="form-control input-sm">
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-10">
                    <div class="form-group fg-line ">
                        <label for="exampleInputRealName">真实姓名</label>
                        <input type="text" disabled="disabled" value="{{ $user->user->realname }}" class="form-control input-sm">
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-10">
                    <div class="form-group fg-line ">
                        <label for="exampleInputEmail1">手机号</label>
                        <input type="text" disabled="disabled" value="{{ $user->tel }}" class="form-control input-sm">
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-10">
                    <div class="form-group fg-line ">
                        <label for="exampleInputEmail1">微信</label>
                        <input type="text" disabled="disabled" value="{{ $user->wechat }}" class="form-control input-sm">
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-10">
                    <div class="form-group fg-line ">
                        <label for="exampleInputEmail1">邮箱</label>
                        <input type="text" disabled="disabled" value="{{ $user->email }}" class="form-control input-sm">
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-10">
                    <div class="form-group fg-line ">
                        <label for="exampleInputEmail1">网址</label>
                        <input type="text" disabled="disabled" value="{{ $user->website }}" class="form-control input-sm">
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-10">
                    <div class="form-group fg-line ">
                        <label for="exampleInputEmail1">地址</label>
                        <input type="text" disabled="disabled" value="{{ $user->address }}" class="form-control input-sm">
                    </div>
                </div>
            </div>


            <div class="row">
                <div class="col-sm-10">
                    <div class="form-group fg-line ">
                        <label for="exampleInputEmail1">卡片生成时间</label>
                        <input type="text" disabled="disabled" value="{{ $user->created_at }}" name="created_at"  class="form-control input-sm">
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-10">
                    <div class="form-group fg-line ">
                        <label for="exampleInputEmail1">简介</label>
                        <textarea class="form-control" disabled="disabled" rows="5" placeholder="" name="bank">
                            {{$user->remark}}
                        </textarea>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-10">
                    <div class="form-group fg-line ">
                        <label>图片</label>
                        @foreach($user->images as $image)
                        <span >
                            <img src='{{$image->url}}?imageslim' style='width:80px;' />
                        </span>
                        @endforeach
                    </div>
                </div>
            </div>
            
            @if(!empty($user->video_url))
            <div class="row">
                <div class="col-sm-12">
                    <div class="form-group fg-line ">
                        <label>视频</label>
                    </div>
                </div>
                <div class="col-md-12 ">
                    <table class="table table-striped table-hover text-left" style="margin-bottom:40px;">
                        <tbody id="fsUploadProgress">
                            <tr id="" class="progressContainer" style="opacity: 1;">
                                <td class="progressName">
                                    <img src='{{ $user->video_url }}?vframe/jpg/offset/1' style='width:300px'/><br/>
                                    {{$user->video_url}}
                                    <div class="m-t-20">
                                        <span class="origin-video btn  btn-primary play-btn" data-url="{{$user->video_url}}">播放视频</span>
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

    var vType = function () {
        $.ajaxSetup({
            headers: ''
        });
        var type = '';
        $.ajax({
            url: vLink + "?stat",
            async: false
        }).done(function (info) {
            type = info.mimeType;
            if (type == 'application/x-mpegurl') {
                type = 'application/x-mpegURL';
            }
        });

        return type;
    };

    var videoEnd = function () {
        console.log('ended');
    };
    var videoPlay = function () {
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
    }, function () {
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


$('#myModal-video').on('hidden.bs.modal', function () {
    disposePlayer();
});

$('tbody').on('click', '.play-btn', function () {
    $('#myModal-video').modal();
    var url = $(this).data('url');
    initPlayer(url);
});
</script>
@endsection