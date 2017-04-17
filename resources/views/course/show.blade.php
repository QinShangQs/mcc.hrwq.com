@extends('layouts.material')
@section('content')
    <div class="container">
        <div class="block-header">
            <h2><a href="{{route('course.index')}}">课程管理</a> -> 课程详情</h2>
        </div>

        <div class="card">
            <div class="card-body card-padding">
                <div class="row">
                    <div class="col-sm-3">
                        <div class="form-group fg-line ">
                            <label for="exampleInputEmail1">课程标题</label>
                            <input type="text" disabled="disabled" value="{{ $course->title }}" name="title"
                                   class="form-control input-sm">
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-3">
                        <div class="form-group fg-line ">
                            <label for="exampleInputEmail1">图片</label>
                            @if($course->picture)
                                <span id="preview_image" class="row"><img id="img0" src="{{ asset($course->picture) }}"></span>
                            @else
                                <span id="preview_image" class="row">暂未上传图片</span>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-3">
                        <div class="form-group fg-line ">
                            <label for="exampleInputEmail1">课程类别</label>
                            <div class="select">
                                <select disabled="disabled" name="agency_id" class="form-control">
                                    <option value="">--请选择课程类别--</option>
                                    @foreach($agencys as $agency)
                                        <option value="{{$agency->id}}"
                                                @if($agency->id == $course->agency_id) selected @endif>{{$agency->agency_name}}</option>
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
                                    <input type="radio" disabled="disabled" name="hardware" value="1"
                                           @if($course->hardware == 1) checked @endif >
                                    <i class="input-helper"></i>
                                    有硬件
                                </label>

                                <label class="radio radio-inline m-r-20">
                                    <input type="radio" disabled="disabled" name="hardware" value="2"
                                           @if($course->hardware == 2) checked @endif >
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
                                    <input type="radio" disabled="disabled" name="is_tutor_course" value="1" @if($course->is_tutor_course == 1) checked @endif>
                                    <i class="input-helper"></i>
                                    是
                                </label>

                                <label class="radio radio-inline m-r-20">
                                    <input type="radio" disabled="disabled" name="is_tutor_course" value="2" @if($course->is_tutor_course == 2 || $course->is_tutor_course == '') checked @endif>
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
                                    <input type="radio" disabled="disabled" name="type" value="1"
                                           @if($course->type == 1) checked @endif >
                                    <i class="input-helper"></i>
                                    免费
                                </label>

                                <label class="radio radio-inline m-r-20">
                                    <input type="radio" disabled="disabled" name="type" value="2"
                                           @if($course->type == 2) checked @endif >
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
                            <input type="text" disabled="disabled" value="{{ $course->price }}" name="price"
                                   class="form-control input-sm">
                        </div>
                    </div>
                </div>

                <div class="row" id="original_price">
                    <div class="col-sm-3">
                        <div class="form-group fg-line ">
                            <label for="exampleInputEmail1">市场价（单位：元）</label>
                            <input type="text" disabled="disabled" value="{{ $course->original_price }}"
                                   name="original_price" class="form-control input-sm">
                        </div>
                    </div>
                </div>

                <div class="row" id="package_price">
                    <div class="col-sm-3">
                        <div class="form-group fg-line ">
                            <label for="exampleInputEmail1">套餐价（单位：元）</label>
                            <input type="text" disabled="disabled" value="{{ $course->package_price }}"
                                   name="package_price" class="form-control input-sm">
                        </div>
                    </div>
                </div>

                <div class="row">
                        <div class="col-sm-3">
                            <div class="form-group fg-line ">
                                <label for="exampleInputEmail1">是否由总部发起</label>
                                <div class="radio m-b-15">
                                    <label class="radio radio-inline m-r-20">
                                        <input disabled="disabled" type="radio" name="head_flg" value="1" @if($course->head_flg==1 || $course->head_flg=='') checked @endif>
                                        <i class="input-helper"></i>
                                        是
                                    </label>

                                    <label class="radio radio-inline m-r-20">
                                        <input disabled="disabled" type="radio" name="head_flg" value="2" @if($course->head_flg==2) checked @endif>
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
                                        <input disabled="disabled" type="radio" name="distribution_flg" value="1" @if($course->distribution_flg==1) checked @endif>
                                        <i class="input-helper"></i>
                                        是
                                    </label>

                                    <label class="radio radio-inline m-r-20">
                                        <input disabled="disabled" type="radio" name="distribution_flg" value="2" @if($course->distribution_flg==2 || $course->distribution_flg=='') checked @endif>
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
                                    <select disabled="disabled" name="city" class="selectpicker" onchange="getpartner(this.value)">
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
                                <input type="hidden" value="{{ $course->promoter }}" name="promoter" id="promoter"  class="form-control input-sm">
                                <input disabled="disabled" type="text" disabled="disabled" value="{{ @$course->user->realname }}" name="promoter_name" id="promoter_name"  class="form-control input-sm">
                            </div>
                        </div>
                    </div>

                <div class="row">
                    <div class="col-sm-3">
                        <div class="form-group fg-line ">
                            <label for="exampleInputEmail1">课程时间</label>
                            <input type="text" disabled="disabled" value="{{ $course->course_date }}" name="course_date"
                                   class="form-control input-sm">
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-3">
                        <div class="form-group fg-line ">
                            <label for="exampleInputEmail1">具体地址</label>
                            <input type="text" disabled="disabled" value="{{ $course->course_addr }}" name="course_addr"
                                   class="form-control input-sm">
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-3">
                        <div class="form-group fg-line ">
                            <label for="exampleInputEmail1">名额</label>
                            <input type="text" disabled="disabled" value="{{ $course->allow_num }}" name="allow_num"
                                   class="form-control input-sm">
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-3">
                        <div class="form-group fg-line ">
                            <label for="exampleInputEmail1">参与人数</label>
                            <input type="text" disabled="disabled" value="{{ $course->participate_num }}"
                                   name="participate_num" class="form-control input-sm">
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-3">
                        <div class="form-group fg-line ">
                            <label for="exampleInputEmail1">咨询电话</label>
                            <input type="text" disabled="disabled" value="{{ $course->tel }}" name="tel"
                                   class="form-control input-sm">
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-3">
                        <div class="form-group fg-line ">
                            <label for="exampleInputEmail1">适用对象</label>
                            <textarea disabled="disabled" class="form-control" rows="5" placeholder=""
                                      name="suitable">{{ $course->suitable }}</textarea>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-3">
                        <div class="form-group fg-line ">
                            <label for="exampleInputEmail1">讲师简介</label>
                            <textarea disabled="disabled" class="form-control" rows="5" placeholder=""
                                      name="teacher_intr">{{ $course->teacher_intr }}</textarea>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-3">
                        <div class="form-group fg-line ">
                            <label for="exampleInputEmail1">课程目标</label>
                            <textarea disabled="disabled" class="form-control" rows="5" placeholder=""
                                      name="course_target">{{ $course->course_target }}</textarea>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-3">
                        <div class="form-group fg-line ">
                            <label for="exampleInputEmail1">课程导向</label>
                            <textarea disabled="disabled" class="form-control" rows="5" placeholder=""
                                      name="course_guide">{{ $course->course_guide }}</textarea>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-3">
                        <div class="form-group fg-line ">
                            <label for="exampleInputEmail1">验证密码</label>
                            <input type="text" disabled="disabled" value="{{ $course->verify_password }}"
                                   name="verify_password" class="form-control input-sm">
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-11">
                        <div class="form-group fg-line ">
                            <label for="exampleInputEmail1">课程安排</label>
                            <textarea disabled="disabled" id="ueditor"
                                      name="course_arrange">{{$course->course_arrange}}</textarea>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-11">
                        <button type="button"
                                class="btn btn-primary waves-effect waves-effect"
                                data-toggle="modal" data-target="#assign-delivery-{{$course->id}}"
                                title="发送开课提醒">发送开课提醒</button>

                        <div id="assign-delivery-{{$course->id}}" class="modal fade" role="dialog"
                             data-id="{{$course->id}}">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <button type="button" class="close"
                                                data-dismiss="modal">&times;</button>
                                        <h4 class="modal-title">发送开课提醒</h4>
                                    </div>
                                    <div class="modal-body">
                                        <textarea name="content" id="" rows="5" class="form-control">你报名的{{$course->title}}快要开课了，请注意安排好行程。父母是孩子最好的老师，祝你生活愉快！客服电话400-6363-555</textarea>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-primary send-notify"
                                                data-id="{{$course->id}}" data-dismiss="modal">
                                            确定
                                        </button>
                                        <button type="button" class="btn btn-default"
                                                data-dismiss="modal">
                                            取消
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
@endsection

@section('script')
    <script type="text/javascript" charset="utf-8" src="/vendors/Ueditor/ueditor.config.js"></script>
    <script type="text/javascript" charset="utf-8" src="/vendors/Ueditor/ueditor.all.min.js"></script>
    <script type="text/javascript" charset="utf-8" src="/vendors/Ueditor/lang/zh-cn/zh-cn.js"></script>
    <script type="text/javascript">
        $(function () {
            UE.getEditor('ueditor', {initialFrameHeight: 300});
        })
    </script>

    <script type="text/javascript">
        $(function () {
            if ($("input[name='type']:checked").val() == 1) {
                $('#price').hide();
                $('#original_price').hide();
                $('#package_price').hide();
            } else if ($("input[name='type']:checked").val() == 2) {
                $('#price').show();
                $('#original_price').show();
                $('#package_price').show();
            }
            //收费类别
            $("input[name='type']").change(function () {
                if ($(this).val() == 1) {
                    $('#price').hide();
                    $('#original_price').hide();
                    $('#package_price').hide();
                } else {
                    $('#price').show();
                    $('#original_price').show();
                    $('#package_price').show();
                }
            });//是否为总部发起
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

            $(document).on('click', 'button.send-notify', function () {
                var id = $(this).data('id');
                $.ajax({
                    type: 'post',
                    url: '{{route('course.send.notify')}}',
                    data: {"id": id, "content": $(this).closest('.modal-dialog').find('textarea').val()},
                    dataType: 'json',
                    success: function (res) {
                        if (res.code == 0) {
                            swal(res.message);
                        } else {
                            swal({
                                title: res.message,
                                type: "error"
                            });
                        }
                    }
                });
            });
        });
    </script>

@endsection
