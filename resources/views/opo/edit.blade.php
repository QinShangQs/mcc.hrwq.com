@extends('layouts.material')
@section('style')
    <link href="/vendors/webuploader/webuploader.css" rel="stylesheet">
@endsection
@section('content')
    <div class="container">
        <div class="block-header">
            <h2><a href="{{route('opo.index')}}">信息维护</a>  -> 编辑产品</h2>
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

                <form id="post_form" action="{{route('opo.update',['id'=>$opo->id])}}" method="post">
                    {!! csrf_field() !!}
                    <input type="hidden" name="id" id="id" value="{{$opo->id}}">

                    <div class="row">
                        <div class="col-sm-3">
                            <div class="form-group fg-line ">
                                <label for="exampleInputEmail1">产品名称</label>
                                <input type="text" value="{{ $opo->title }}" name="title"  class="form-control input-sm">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-3">
                            <div class="form-group fg-line ">
                                <label for="exampleInputEmail1">价格</label>
                                <input type="text" value="{{ $opo->price }}" name="price"  class="form-control input-sm">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-3">
                            <div class="form-group fg-line ">
                                <label>产品标题</label>
                                <input type="text" value="{{ $opo->project_title }}" name="project_title"  class="form-control input-sm">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-11">
                            <div class="form-group fg-line ">
                                <label for="exampleInputEmail1">项目介绍</label>
                                <textarea  id="ueditor" name="project_intr" >{{$opo->project_intr}}</textarea>
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
                                    <input type="hidden" id="cover_image" name="picture" value="{{ $opo->picture }}" />
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-3">
                            <div class="form-group fg-line ">
                                <label>当前图片</label>
                                @if($opo->picture)
                                    <span  id="preview_image"  class="row"><img id="img0" src="{{ asset($opo->picture) }}"></span>
                                @else
                                    <span  id="preview_image"  class="row">暂未上传图片</span>
                                @endif
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
    <script type="text/javascript" src="/vendors/webuploader/opo_webupload.js"></script>

    <script type="text/javascript" charset="utf-8" src="/vendors/Ueditor/ueditor.config.js"></script>
    <script type="text/javascript" charset="utf-8" src="/vendors/Ueditor/ueditor.all.min.js"> </script>
    <script type="text/javascript" charset="utf-8" src="/vendors/Ueditor/lang/zh-cn/zh-cn.js"></script>
    <script type="text/javascript">
        $(function(){
            UE.getEditor('ueditor');
        })
    </script>
@endsection