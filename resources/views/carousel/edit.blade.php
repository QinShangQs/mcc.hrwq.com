@extends('layouts.material')
@section('style')
    <link href="/vendors/webuploader/webuploader.css" rel="stylesheet">
@endsection
@section('content')
    <div class="container">
        <div class="block-header">
            <h2><a href="{{route('carousel')}}">轮播图列表</a>  -> 编辑轮播图</h2>
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

                    <form id="post_form" action="{{route('carousel.update',['id'=>$data->id])}}" method="post">
                        {!! csrf_field() !!}
                        <div class="row">
                            <div class="col-sm-3">
                                <div class="form-group fg-line ">
                                    <label>名称</label>
                                    <input type="text" value="{{$data->title}}"  name="title"    class="form-control input-sm" >
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-2">
                                <div class="form-group fg-line ">
                                    <label>显示位置</label>
                                    <select class="selectpicker"    name="use_type">
                                        <option value=""  >--请选择类型--</option>
                                        @foreach($use_type as $k=>$v)
                                            <option value="{{ $k }}" @if($k==$data->use_type)) selected @endif>{{$v}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group fg-line ">
                                    <label>图片 <small class="c-red">(限1张,大小限制2MB)</small></label>
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
                                        <input type="hidden" id="cover_image" name="image_url" value="{{$data->image_url}}" />
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-2">
                                <div class="form-group fg-line ">
                                    <label>当前图片</label>
                                    <span  id="preview_image"  class="row"><img id="img0" src="{{asset($data->image_thumb_url)}}"></span>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-2">
                                <div class="form-group fg-line ">
                                    <label>排序<small class="c-red"> (数字越大越靠前)</small></label>
                                    <input type="text" value="{{$data->sort}}"  name="sort"   class="form-control input-sm" >
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-2">
                                <div class="form-group fg-line ">
                                    <label>跳转类型</label>
                                    <select id="selectId"  name="redirect_type" class="selectpicker" >
                                        <option value=""  >--请选择类型--</option>
                                        @foreach($type as $k=>$v)
                                            <option value="{{ $k }}" @if($k==$data->redirect_type) selected @endif>{{$v}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="row r_item r_url">
                            <div class="col-sm-3">
                                <div class="form-group fg-line ">
                                    <label>链接地址</label>
                                    <input type="text" value="{{$data->redirect_url}}"  name="redirect_url"    class="form-control input-sm" >
                                </div>
                            </div>
                        </div>

                        <div class="row r_item r_cnt">
                            <div class="col-sm-12">
                                <div class="form-group fg-line ">
                                    <label>静态页内容</label>
                                    <textarea  id="ueditor" name="redirect_content" >{{$data->redirect_content}}</textarea>
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

    <script type="text/javascript" charset="utf-8" src="/vendors/Ueditor/ueditor.config.js"></script>
    <script type="text/javascript" charset="utf-8" src="/vendors/Ueditor/ueditor.all.min.js"> </script>
    <script type="text/javascript" charset="utf-8" src="/vendors/Ueditor/lang/zh-cn/zh-cn.js"></script>
    <script type="text/javascript">
        $(function(){
            UE.getEditor('ueditor',{initialFrameHeight:300});
        })

        /** 类型选择 */
        $("#selectId").change(function(){
            var selectId = $(this).val();
            showDiv(selectId);
        });
        showDiv($("#selectId").val());
        function showDiv(selectId){
            $('.r_item').hide();
            if(selectId==2){
                $('.r_url').show();
            }else if(selectId==3) {
                $('.r_cnt').show();
            }
        }
    </script>
@endsection