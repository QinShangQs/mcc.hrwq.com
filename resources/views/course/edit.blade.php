@extends('layouts.material')
@section('style')
    <link href="/vendors/webuploader/webuploader.css" rel="stylesheet">
@endsection
@section('content')
    <div class="container">
        <div class="block-header">
            <h2><a href="{{route('course.index')}}">课程管理</a> -> 编辑课程</h2>
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

                <form id="post_form"  action="{{route('course.update',['id'=>$course->id])}}" method="post">
                    {!! csrf_field() !!}
                    <input type="hidden" name="id" id="id" value="{{$course->id}}">

                    <div class="row">
                        <div class="col-sm-3">
                            <div class="form-group fg-line ">
                                <label>课程标题 <small class="c-red">(15个汉字字以内)</small></label>
                                <input type="text" value="{{ $course->title }}" name="title"  class="form-control input-sm">
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
                                    <input type="hidden" id="cover_image" name="picture" value="{{ $course->picture }}" />
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-3">
                            <div class="form-group fg-line ">
                                <label>当前图片</label>
                                @if($course->picture)
                                    <span  id="preview_image"  class="row"><img id="img0" src="{{ asset($course->picture) }}"></span>
                                @else
                                    <span  id="preview_image"  class="row">暂未上传图片</span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-3">
                            <div class="form-group fg-line ">
                                <label for="exampleInputEmail1">课程类别</label>
                                <div class="select">
                                    <select name="agency_id" class="form-control" >
                                        <option value="">--请选择课程类别--</option>
                                        @foreach($agencys as $agency)
                                            <option value="{{$agency->id}}" @if($agency->id == $course->agency_id) selected @endif>{{$agency->agency_name}}</option>
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
                                        <input type="radio" name="hardware" value="1" @if($course->hardware == 1) checked @endif >
                                        <i class="input-helper"></i>
                                        有硬件
                                    </label>
                                    
                                    <label class="radio radio-inline m-r-20">
                                        <input type="radio" name="hardware" value="2"  @if($course->hardware == 2) checked @endif >
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
                                <label for="exampleInputEmail1">是否为指导师培训课程</label>
                                <div class="radio m-b-15">
                                    <label class="radio radio-inline m-r-20">
                                        <input type="radio" name="is_tutor_course" value="1" @if($course->is_tutor_course == 1) checked @endif>
                                        <i class="input-helper"></i>
                                        是
                                    </label>

                                    <label class="radio radio-inline m-r-20">
                                        <input type="radio" name="is_tutor_course" value="2" @if($course->is_tutor_course == 2 || $course->is_tutor_course == '') checked @endif>
                                        <i class="input-helper"></i>
                                        否
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group fg-line ">
                                <label for="exampleInputEmail1">收费类型</label>
                                <div class="radio m-b-15">
                                    <label class="radio radio-inline m-r-20">
                                        <input onclick="changeType()" type="radio" name="type" value="1" @if($course->type == 1) checked @endif >
                                        <i class="input-helper"></i>
                                        免费
                                    </label>
                                    
                                    <label class="radio radio-inline m-r-20">
                                        <input onclick="changeType()" type="radio" name="type" value="2"  @if($course->type == 2) checked @endif >
                                        <i class="input-helper"></i>
                                        收费
                                    </label>
                                    
                                    <label class="radio radio-inline m-r-20">
                                        <input onclick="changeType()" type="radio" name="type" value="3" @if($course->type == 3) checked @endif>
                                        <i class="input-helper"></i>
                                        团购
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row" id="price">
                        <div class="col-sm-3">
                            <div class="form-group fg-line ">
                                <label for="exampleInputEmail1">当前价（单位：元）</label>
                                <input type="text" value="{{ $course->price }}" name="price"  class="form-control input-sm">
                            </div>
                        </div>
                    </div>

                    <div class="row" id="original_price">
                        <div class="col-sm-3">
                            <div class="form-group fg-line ">
                                <label for="exampleInputEmail1">市场价（单位：元）</label>
                                <input type="text" value="{{ $course->original_price }}" name="original_price"  class="form-control input-sm">
                            </div>
                        </div>
                    </div>

                    <div class="row" id="package_price">
                        <div class="col-sm-3">
                            <div class="form-group fg-line ">
                                <label for="exampleInputEmail1">套餐价（单位：元）</label>
                                <input type="text" value="{{ $course->package_price }}" name="package_price"  class="form-control input-sm">
                            </div>
                        </div>
                    </div>

                    <div class="row" id="tuangou_price">
                        <div class="col-sm-3">
                            <div class="form-group fg-line ">
                                <label for="exampleInputEmail1">团购价（单位：元）</label>
                                <input type="text" value="{{$course->tuangou_price}}" name="tuangou_price"  class="form-control input-sm">
                            </div>
                        </div>
                    </div>
                    <div class="row" id="tuangou_peoples">
                        <div class="col-sm-3">
                            <div class="form-group fg-line ">
                                <label for="exampleInputEmail1">组团人数</label>
                                <input type="text" value="{{$course->tuangou_peoples}}" name="tuangou_peoples"  class="form-control input-sm">
                            </div>
                        </div>
                    </div>
                    <div class="row" id="tuangou_days">
                        <div class="col-sm-3">
                            <div class="form-group fg-line ">
                                <label for="exampleInputEmail1">截团天数</label>
                                <input type="text" value="{{$course->tuangou_days}}" name="tuangou_days"  class="form-control input-sm">
                            </div>
                        </div>
                    </div>
                        
                    <div class="row">
                        <div class="col-sm-3">
                            <div class="form-group fg-line ">
                                <label for="exampleInputEmail1">是否由总部发起</label>
                                <div class="radio m-b-15">
                                    <label class="radio radio-inline m-r-20">
                                        <input type="radio" name="head_flg" value="1" @if($course->head_flg==1 || $course->head_flg=='') checked @endif>
                                        <i class="input-helper"></i>
                                        是
                                    </label>

                                    <label class="radio radio-inline m-r-20">
                                        <input type="radio" name="head_flg" value="2" @if($course->head_flg==2) checked @endif>
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
                                        <input type="radio" name="distribution_flg" value="1" @if($course->distribution_flg==1) checked @endif>
                                        <i class="input-helper"></i>
                                        是
                                    </label>

                                    <label class="radio radio-inline m-r-20">
                                        <input type="radio" name="distribution_flg" value="2" @if($course->distribution_flg==2 || $course->distribution_flg=='') checked @endif>
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
                                    <select name="city" id="city" class="selectpicker" onchange="getpartner(this.value)">
                                        <option value="">请选择城市</option>
                                        @foreach($partnerCitys as $partnerCity)
                                            <option value="{{$partnerCity->area_id}}" @if($course->city == $partnerCity->area_id) selected @endif >{{$partnerCity->area_name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-3">                    
                            <div class="form-group fg-line ">
                                <label for="exampleInputEmail1">发起人</label>
                                <div class="select">
                                    <select name="promoter" id="promoter" class="form-control" >
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-3">
                            <div class="form-group fg-line ">
                                <label for="exampleInputEmail1">课程时间</label>
                                <textarea type="text" rows="3"  name="course_date"  class="form-control input-sm">{{$course->course_date}}</textarea>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-3">
                            <div class="form-group fg-line ">
                                <label for="exampleInputEmail1">具体地址</label>
                                <input type="text" value="{{ $course->course_addr }}" name="course_addr"  class="form-control input-sm">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-3">
                            <div class="form-group fg-line ">
                                <label for="exampleInputEmail1">名额</label>
                                <input type="text" value="{{ $course->allow_num }}" name="allow_num"  class="form-control input-sm">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-3">
                            <div class="form-group fg-line ">
                                <label for="exampleInputEmail1">参与人数</label>
                                <input type="text" value="{{ $course->participate_num }}" name="participate_num"  class="form-control input-sm">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-3">
                            <div class="form-group fg-line ">
                                <label for="exampleInputEmail1">咨询电话</label>
                                <input type="text" value="{{ $course->tel }}" name="tel"  class="form-control input-sm">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-3">
                            <div class="form-group fg-line ">
                                <label for="exampleInputEmail1">适用对象</label>
                                <textarea class="form-control" rows="5" placeholder="" name="suitable">{{ $course->suitable }}</textarea>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-3">
                            <div class="form-group fg-line ">
                                <label for="exampleInputEmail1">讲师简介</label>
                                <textarea class="form-control" rows="5" placeholder="" name="teacher_intr">{{ $course->teacher_intr }}</textarea>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-3">
                            <div class="form-group fg-line ">
                                <label for="exampleInputEmail1">课程目标</label>
                                <textarea class="form-control" rows="5" placeholder="" name="course_target">{{ $course->course_target }}</textarea>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-3">                            
                            <div class="form-group fg-line ">
                                <label for="exampleInputEmail1">验证密码</label>
                                <input type="text" value="{{ $course->verify_password }}" name="verify_password"  class="form-control input-sm">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-11">
                            <div class="form-group fg-line ">
                                <label for="exampleInputEmail1">课程安排</label>
                                <textarea  id="ueditor" name="course_arrange" >{{$course->course_arrange}}</textarea>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-3">
                            <div class="form-group fg-line ">
                                <label>排序值<small class="c-red">(数字越大越靠前)</small></label>
                                <input type="text" value="{{$course->sort}}" name="sort" placeholder="" class="form-control input-sm">
                            </div>
                        </div>
                    </div>

                    <div class="form-group fg-line">
                        @if($course->status == 1 || $course->status == 2)
                        <button class="btn bgm-cyan waves-effect" >保存</button>
                        @endif

                        @if($course->status == 1 || $course->status == 2)
                        <button class="btn bgm-lightblue waves-effect" id="release">发布</button>
                        @endif
                        
                        @if($course->status == 2)
                        <button class="btn btn-danger waves-effect" id="off">下架</button>
                        @endif
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
            $("#promoter").empty();
            for(i = 0; i < arrpartner.length ; i ++){
                $("#promoter").append('<option '+(i == 0 ? "selected":"")+' value="'+arrpartner[i].id+'">'+arrpartner[i].realname+'</option>');
            }
        }
    </script>

    <script type="text/javascript">
        function changeType(){
            if ($("input[name='type']:checked").val()==1) {
               $('#price').hide();
               $('#original_price').hide();
               $('#package_price').hide();
               $('#tuangou_price').hide();
               $('#tuangou_peoples').hide();
               $('#tuangou_days').hide();
            } else if ($("input[name='type']:checked").val()==2) {
               $('#price').show();
               $('#original_price').show();
               $('#package_price').show();
               $('#tuangou_price').hide();
               $('#tuangou_peoples').hide();
               $('#tuangou_days').hide();
            } else if ($("input[name='type']:checked").val()==3) {
               $('#price').show();
               $('#original_price').show();
               $('#package_price').hide();
               $('#tuangou_price').show();
               $('#tuangou_peoples').show();
               $('#tuangou_days').show();
            }
        }
        
        $(function(){
            if($('#city').val()){
                getpartner($('#city').val());
                $("#promoter").val({{$course->promoter}});
            }
            
            changeType();

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

            // 发布
            $("#release").click(function(){
              var id = $('#id').val();

              $.ajax({
                    type: 'post',
                    url: '{{route('course.release')}}',
                    data: {id: id},
                    success: function (res) {
                        if (res.code == 0) {
                            swal(res.message, "", "success");
                        } else {
                            swal(res.message);
                        }
                    }
                });
            });

            // 下架
            $("#off").click(function(){
              var id = $('#id').val();
              $.ajax({
                    type: 'post',
                    url: '{{route('course.off')}}',
                    data: {id: id},
                    success: function (res) {
                        if (res.code == 0) {
                            swal(res.message, "", "success");
                        } else {
                            swal(res.message);
                        }
                    }
                });
            });

        })
    </script>

@endsection
