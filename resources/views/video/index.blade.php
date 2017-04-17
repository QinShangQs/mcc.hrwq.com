@extends('layouts.material')
<link href="/qiniu/js/videojs/video-js.css" rel="stylesheet">
@section('content')
    <div class="container">
        <div class="block-header">
            <h2>视频列表</h2>
        </div>
        <div class="card">
            <div class="card-body card-padding">
                <div class="row">
                    <div class="col-sm-4">
                        <p class="f-500 c-black m-b-20">上传教学视频</p>
                        <div class="fileinput fileinput-new" data-provides="fileinput">
                            <input type="hidden" id="domain" value="{$domain}">
                            <input type="hidden" id="uptoken_url" value="{$uptokenUrl}">
                            <span class="btn btn-primary btn-file m-r-10 waves-effect">
                                <span class="fileinput-new">选择文件</span>
                                <span class="fileinput-exists">Change</span>
                                <input type="hidden" value="" name="..."><input type="file" name="">
                            </span>
                            <span class="fileinput-filename"></span>
                            <a href="#" class="close fileinput-exists" data-dismiss="fileinput">×</a>
                        </div>
                    </div>
                </div>
                <div class="alert alert-success" role="alert" id="success" style="display:none">队列全部文件处理完毕</div>
            </div>

            <div class="table-responsive">
            <table id="data-table-selection" class="table table-striped">
                <thead>
                    <tr>
                        <th data-column-id="id" data-type="numeric" data-identifier="true">文件名</th>
                        <th data-column-id="sender">大小</th>
                        <th data-column-id="mendian" data-order="desc">详细</th>
                        <th data-column-id="caozuo" data-order="desc" data-order="desc">操作</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>1.mp4</td>
                        <td>28.8 MB</td>
                        <td><strong>Link:</strong><a href="http://devtest.qiniudn.com/1.mp4" target="_blank"> http://devtest.qiniudn.com/1.mp4</a></td>
                        <td>
                            <button data-url="http://devtest.qiniudn.com/1.mp4" class="btn btn-primary btn-sm m-t-10 waves-effect play-btn">播放原视频</button>
                            <button data-url="http://devtest.qiniudn.com/0JmbiheSQfkOB1X6h40wCvDb0YY%3D%2FlolDkdQ10vxTTlQMDruPvj1VzNUi" class="btn btn-primary btn-sm m-t-10 waves-effect play-btn">播放转码后视频</button>
                        </td>
                    </tr>
                </tbody>
            </table>
          </div>
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
                            <div id="video-container" style="margin:-20px;border:0px solid #999;">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
@endsection
@section('script')
<script type="text/javascript" src="/qiniu/js/videojs/video.js"></script>
<script type="text/javascript" src="/qiniu/js/plupload/plupload.full.min.js"></script>
<script type="text/javascript" src="/qiniu/js/plupload/i18n/zh_CN.js"></script>
<script type="text/javascript" src="/qiniu/js/qiniu.js"></script>
<script type="text/javascript" src="/qiniu/js/main.js"></script>
<script type="text/javascript" src="/qiniu/js/ui.js"></script>
<script>
    videojs.options.flash.swf = "/qiniu/js/videojs/video-js.swf";
</script>
@endsection