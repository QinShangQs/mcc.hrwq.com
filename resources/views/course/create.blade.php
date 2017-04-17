@extends('layouts.material')
@section('style')
    <link href="/vendors/webuploader/webuploader.css" rel="stylesheet">
@endsection
@section('content')
    <div class="container">
        <div class="block-header">
            <h2><a href="{{route('course.index')}}">课程管理</a>  -> 添加课程</h2>
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

                <form id="post_form" action="{{route('course.store')}}" method="post">
                    {!! csrf_field() !!}

                    <div class="row">
                        <div class="col-sm-3">
                            <div class="form-group fg-line ">
                                <label for="exampleInputEmail1">课程标题 <small class="c-red">(15个汉字字以内)</small></label>
                                <input type="text" value="{{old('title')}}" name="title"  class="form-control input-sm">
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
                                    <input type="hidden" id="cover_image" name="picture" value="" />
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-3">
                            <div class="form-group fg-line ">
                                <label for="exampleInputEmail1">课程类别</label>
                                <div class="select">
                                    <select class="selectpicker" name="agency_id"   >
                                        <option value="">请选择课程类别</option>
                                        @foreach($agencys as $agency)
                                            <option value="{{$agency->id}}" @if($agency->id == old('title')) selected @endif>{{$agency->agency_name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-3">
                            <div class="form-group fg-line ">
                                <label for="exampleInputEmail1">是否有硬件</label>
                                <div class="radio m-b-15">
                                    <label class="radio radio-inline m-r-20">
                                        <input type="radio" name="hardware" value="1" @if(old('hardware')==1 or !old('hardware')) checked @endif>
                                        <i class="input-helper"></i>
                                        有硬件
                                    </label>
                                    
                                    <label class="radio radio-inline m-r-20">
                                        <input type="radio" name="hardware" value="2" @if(old('hardware')==2) checked @endif>
                                        <i class="input-helper"></i>
                                        无硬件
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-3">
                            <div class="form-group fg-line ">
                                <label for="exampleInputEmail1">是否指导师培训视频</label>
                                <div class="radio m-b-15">
                                    <label class="radio radio-inline m-r-20">
                                        <input type="radio" name="is_tutor_course" value="1" @if(old('is_tutor_course')==1) checked @endif>
                                        <i class="input-helper"></i>
                                        是
                                    </label>

                                    <label class="radio radio-inline m-r-20">
                                        <input type="radio" name="is_tutor_course" value="2" @if(old('is_tutor_course')==2 || old('is_tutor_course')=='') checked @endif>
                                        <i class="input-helper"></i>
                                        否
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-3">
                            <div class="form-group fg-line ">
                                <label for="exampleInputEmail1">收费类型</label>
                                <div class="radio m-b-15">
                                    <label class="radio radio-inline m-r-20">
                                        <input type="radio" name="type" value="1" @if(old('type')==1 or !old('type')) checked @endif>
                                        <i class="input-helper"></i>
                                        免费
                                    </label>
                                    
                                    <label class="radio radio-inline m-r-20">
                                        <input type="radio" name="type" value="2" @if(old('type')==2) checked @endif>
                                        <i class="input-helper"></i>
                                        收费
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row" id="price">
                        <div class="col-sm-3">
                            <div class="form-group fg-line ">
                                <label for="exampleInputEmail1">当前价（单位：元）</label>
                                <input type="text" value="{{old('price')}}" name="price"  class="form-control input-sm">
                            </div>
                        </div>
                    </div>

                    <div class="row" id="original_price">
                        <div class="col-sm-3">
                            <div class="form-group fg-line ">
                                <label for="exampleInputEmail1">市场价（单位：元）</label>
                                <input type="text" value="{{old('original_price')}}" name="original_price"  class="form-control input-sm">
                            </div>
                        </div>
                    </div>

                    <div class="row" id="package_price">
                        <div class="col-sm-3">
                            <div class="form-group fg-line ">
                                <label for="exampleInputEmail1">套餐价（单位：元）</label>
                                <input type="text" value="{{old('package_price')}}" name="package_price"  class="form-control input-sm">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-3">
                            <div class="form-group fg-line ">
                                <label for="exampleInputEmail1">是否由总部发起</label>
                                <div class="radio m-b-15">
                                    <label class="radio radio-inline m-r-20">
                                        <input type="radio" name="head_flg" value="1" @if(old('head_flg')==1 || old('head_flg')=='') checked @endif>
                                        <i class="input-helper"></i>
                                        是
                                    </label>

                                    <label class="radio radio-inline m-r-20">
                                        <input type="radio" name="head_flg" value="2" @if(old('head_flg')==2) checked @endif>
                                        <i class="input-helper"></i>
                                        否
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-3" id="distribution">
                            <div class="form-group fg-line ">
                                <label for="exampleInputEmail1">是否为可分销产品</label>
                                <div class="radio m-b-15">
                                    <label class="radio radio-inline m-r-20">
                                        <input type="radio" name="distribution_flg" value="1" @if(old('distribution_flg')==1) checked @endif>
                                        <i class="input-helper"></i>
                                        是
                                    </label>

                                    <label class="radio radio-inline m-r-20">
                                        <input type="radio" name="distribution_flg" value="2" @if(old('distribution_flg')==2 || old('distribution_flg')=='') checked @endif>
                                        <i class="input-helper"></i>
                                        否
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row" id="partner_city">
                        <div class="col-sm-3">
                            <div class="form-group fg-line ">
                                <label for="exampleInputEmail1">城市</label>
                                <div class="select">
                                    <select name="city" class="selectpicker" onchange="getpartner(this.value)">
                                        <option value="">请选择城市</option>
                                        @foreach($partnerCitys as $partnerCity)
                                            <option value="{{$partnerCity->area_id}}" @if(old('city') == $partnerCity->area_id) @endif >{{$partnerCity->area_name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-3">                    
                            <div class="form-group fg-line ">
                                <label for="exampleInputEmail1">发起人</label>
                                <input type="hidden" value="" name="promoter" id="promoter"  class="form-control input-sm">
                                <input type="text" disabled="disabled" value="{{old('promoter_name')}}" name="promoter_name" id="promoter_name"  class="form-control input-sm">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-3">
                            <div class="form-group fg-line ">
                                <label for="exampleInputEmail1">课程时间</label>
                                <input type="text" value="{{old('course_date')}}" name="course_date"  class="form-control input-sm">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-3">
                            <div class="form-group fg-line ">
                                <label for="exampleInputEmail1">具体地址</label>
                                <input type="text" value="{{old('course_addr')}}" name="course_addr"  class="form-control input-sm">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-3">
                            <div class="form-group fg-line ">
                                <label for="exampleInputEmail1">名额</label>
                                <input type="text" value="{{old('allow_num')}}" name="allow_num"  class="form-control input-sm">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-3">
                            <div class="form-group fg-line ">
                                <label for="exampleInputEmail1">参与人数</label>
                                <input type="text" value="{{old('participate_num')}}" name="participate_num"  class="form-control input-sm">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-3">
                            <div class="form-group fg-line ">
                                <label for="exampleInputEmail1">咨询电话</label>
                                <input type="text" value="{{old('tel')}}" name="tel"  class="form-control input-sm">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-3">
                            <div class="form-group fg-line ">
                                <label for="exampleInputEmail1">适用对象</label>
                                <textarea class="form-control" rows="5" placeholder="" name="suitable">{{old('suitable')}}</textarea>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-3">
                            <div class="form-group fg-line ">
                                <label for="exampleInputEmail1">讲师简介</label>
                                <textarea class="form-control" rows="5" placeholder="" name="teacher_intr">{{old('teacher_intr')}}</textarea>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-3">
                            <div class="form-group fg-line ">
                                <label for="exampleInputEmail1">课程目标</label>
                                <textarea class="form-control" rows="5" placeholder="" name="course_target">{{old('course_target')}}</textarea>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-3">
                            <div class="form-group fg-line ">
                                <label for="exampleInputEmail1">验证密码</label>
                                <input type="text" value="{{old('verify_password')}}" name="verify_password"  class="form-control input-sm">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-11">
                            <div class="form-group fg-line ">
                                <label for="exampleInputEmail1">课程安排</label>
                                <textarea  id="ueditor" name="course_arrange" >{{old('course_arrange')}}</textarea>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-3">
                            <div class="form-group fg-line ">
                                <label>排序值<small class="c-red">(数字越大越靠前)</small></label>
                                <input type="text" value="{{old('sort')}}" name="sort" placeholder="" class="form-control input-sm">
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
    <script type="text/javascript" src="/vendors/webuploader/course_webupload.js"></script>

    <script type="text/javascript" charset="utf-8" src="/vendors/Ueditor/ueditor.config.js"></script>
    <script type="text/javascript" charset="utf-8" src="/vendors/Ueditor/ueditor.all.min.js"> </script>
    <script type="text/javascript" charset="utf-8" src="/vendors/Ueditor/lang/zh-cn/zh-cn.js"></script>
    
    <script type="text/javascript">
        $(function(){
            UE.getEditor('ueditor',{initialFrameHeight:300});
        })
    </script>

    <script type="text/javascript">
        // 获取发起人(合伙人)
        function getpartner(obj){
            var arrpartner = new Array();
            arrpartner = <?php print_r($arrPartners); ?>;
            arrpartner = arrpartner[obj];
            $("#promoter").val(arrpartner[0]['id']);
            $("#promoter_name").val(arrpartner[0]['realname']);
        }
    </script>

    <script type="text/javascript">
        $(function(){
            if ($("input[name='type']:checked").val()==1) {
               $('#price').hide();
               $('#original_price').hide();
               $('#package_price').hide();
            } else if ($("input[name='type']:checked").val()==2) {
               $('#price').show();
               $('#original_price').show();
               $('#package_price').show();
            }
            //收费类别
            $("input[name='type']").change(function(){
               if ($(this).val()==1) {
                  $('#price').hide();
                  $('#original_price').hide();
                  $('#package_price').hide();
               } else {
                  $('#price').show();
                  $('#original_price').show();
                  $('#package_price').show();
               }
            });

            //是否为总部发起
            if ($("input[name='head_flg']:checked").val()==1) {
               $('#partner_city').hide();
               $('#distribution').show();
            } else if ($("input[name='head_flg']:checked").val()==2) {
               $('#distribution').hide();
               $('#partner_city').show();
            }
            //收费类别
            $("input[name='head_flg']").change(function(){
               if ($(this).val()==1) {
                  $('#partner_city').hide();
                  $('#distribution').show();
               } else {
                  $('#distribution').hide();
                  $('#partner_city').show();
               }
            });
        })
    </script>

@endsection
