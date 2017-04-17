@extends('layouts.material')
@section('style')
    <link href="/vendors/webuploader/webuploader.css" rel="stylesheet">
@endsection
@section('content')
<div class="container">
    <div class="block-header">
        <h2><a href="{{route('order.order_vcourse')}}">壹家壹订单</a>  -> 壹家壹订单详情</h2>
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
            <form id="post_form" action="" method="post">
            {!! csrf_field() !!}
            {{--<input type="hidden" name="id" id="id" value="{{@$order->order_opo->id}}">--}}
            <input type="hidden" id="domain" value="{{config('qiniu.DOMAIN')}}">
            <input type="hidden" id="uptoken_url" value="{{route('order.qiniu_uptoken')}}">
            <input type="hidden" id="delete_url" value="{{route('order.qiniu_delete')}}">
            <div class="row">
                <div class="col-sm-2">
                    <div class="form-group fg-line ">
                        <label>订单号</label>
                        <input type="text" value="{{$order->order_code}}" name="order_code"  class="form-control input-sm" disabled="disabled">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-2">
                    <div class="form-group fg-line ">
                        <label>用户</label>
                        <input type="text" value="{{@$order->user->nickname}}" name="order_name"  class="form-control input-sm" disabled="disabled">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-2">
                    <div class="form-group fg-line ">
                        <label>下单时间</label>
                        <input type="text" value="{{@$order->created_at}}" name="created_at"  class="form-control input-sm" disabled="disabled">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-2">
                    <div class="form-group fg-line ">
                        <label>支付方式</label>
                        <input type="text" value="{{@$pay_type[$order->pay_method]}}" name="pay_method"  class="form-control input-sm" disabled="disabled">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-2">
                    <div class="form-group fg-line ">
                        <label>总价</label>
                        <input type="text" value="{{@$order->total_price}}" name="total_price"  class="form-control input-sm" disabled="disabled">
                    </div>
                </div>
                <div class="col-sm-2">
                    <div class="form-group fg-line ">
                        <label>积分减免金额</label>
                        <input type="text" value="{{@$order->point_price}}" name="point_price"  class="form-control input-sm" disabled="disabled">
                    </div>
                </div>
                <div class="col-sm-2">
                    <div class="form-group fg-line ">
                        <label>优惠券减免金额</label>
                        <input type="text" value="{{@$order->coupon_price}}" name="coupon_price"  class="form-control input-sm" disabled="disabled">
                    </div>
                </div>
                <div class="col-sm-2">
                    <div class="form-group fg-line ">
                        <label>余额减免金额</label>
                        <input type="text" value="{{@$order->balance_price}}" name="balance_price"  class="form-control input-sm" disabled="disabled">
                    </div>
                </div>
                <div class="col-sm-2">
                    <div class="form-group fg-line ">
                        <label>实际支付金额</label>
                        <input type="text" value="{{@$order->price}}" name="price"  class="form-control input-sm" disabled="disabled">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-2">
                    <div class="form-group fg-line ">
                        <label>付款状态<small class="c-red">(可修改)</small></label>
                        <select class="selectpicker" size="10"  name="order_type">
                            @foreach ($order_type as $key=>$item)
                                <option value="{{$key}}" @if($order->order_type==$key) selected @endif>{{$item}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-sm-2">
                    <div class="form-group fg-line ">
                        <label>付款时间<small class="c-red">(可修改)</small></label>
                        <input type="text" value="{{@$order->pay_time}}" name="pay_time"  class="form-control input-sm date-time-picker">
                    </div>
                </div>
            </div>
                <div class="row">
                        @if (!empty(@$order->cert_pic) and @$order->order_type=='2')
                            <div class="col-sm-5" >
                                <div class="form-group fg-line">
                                    <label>付款凭证：</label>
                                    <div class="cert_pic">
                                        <img style="width:400px;" src="{{asset(@$order->cert_pic)}}">
                                        <br/>
                                    </div>
                                </div>
                            </div>
                        @endif
                        @if (!empty(@$order->pay_remark) and @$order->order_type=='2')
                            <div class="col-sm-5" >
                                <div class="form-group fg-line">
                                    <label>付款备注：</label>
                                    <textarea  disabled="disabled" name="pay_remark" cols="50" >{{$order->pay_remark}}"</textarea>
                                </div>
                            </div>
                        @endif
                </div>
                <div class="row">
                    <div class="col-sm-3" style="display:block">
                        <div class="form-group fg-line ">
                            <label for="exampleInputEmail1">上传付款凭证</label>
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
                                <input type="hidden" id="cover_image" name="cert_pic" value="{{@$order->cert_pic}}"/>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-3" >
                        <div class="form-group fg-line">
                            <label>付款备注</label>
                            <textarea  name="pay_remark" cols="50" >{{$order->pay_remark}}"</textarea>
                        </div>
                    </div>
                </div>
            <div class="row">
                <div class="col-sm-2">
                    <div class="form-group fg-line ">
                        <label>服务流程进度<small class="c-red">(可修改)</small></label>
                        <select class="selectpicker" size="10"  name="process">
                                <option value="0">预约成功</option>
                            @foreach ($opo_process as $key=>$item)
                                <option value="{{$key}}" @if(@$order->order_opo->process==$key) selected @endif>{{$item}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
            <div id="service">
                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group fg-line ">
                            <label>服务日志描述</label>
                            <textarea class="form-control" rows="5" placeholder="" name="service_comment">{{@$order->order_opo->service_comment}}</textarea>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12">
                        <div class="form-group fg-line ">
                            <label>服务日志上传<small class="c-red">(不能与其他用户日志重名)</small></label>
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
                                    <th class="col-md-5">文件名</th>
                                    <th class="col-md-1">大小</th>
                                    <th class="col-md-6">详细</th>
                                </tr>
                            </thead>
                            <tbody id="fsUploadProgress">
                                @if(@$order->order_opo->service_url)
                                <tr id="" class="progressContainer" style="opacity: 1;">
                                  <td class="progressName">{{@$order->order_opo->service_url}}
                                    <div class="m-t-20">
                                      <span class="origin-video btn  btn-primary delete-btn m-l-5" style="" data-keya="{{@$order->order_opo->service_url}}">删除文件</span>
                                      <input type="hidden" name="video_original" value="{{@$order->order_opo->service_url}}">
                                    </div>
                                  </td>
                                  <td></td>
                                  <td>
                                      <div class="info">
                                          <strong>Link:</strong>
                                          <a href="{{config('qiniu.DOMAIN').urlencode(@$order->order_opo->service_url)}}" target="_blank"> {{config('qiniu.DOMAIN').@$order->order_opo->service_url}}</a>
                                      </div>
                                  </td>
                                </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="form-group fg-line">
                <a class="btn bgm-cyan waves-effect" >保存</a>
            </div>
          </form>
        </div>
    </div>
</div>
@endsection

@section('script')
<script type="text/javascript" src="/qiniu/js/plupload/plupload.full.min.js"></script>
<script type="text/javascript" src="/qiniu/js/plupload/i18n/zh_CN.js"></script>
<script type="text/javascript" src="/qiniu/js/qiniu.js"></script>
<script type="text/javascript" src="/qiniu/js/main.opo.js"></script>
<script type="text/javascript" src="/qiniu/js/ui.opo.js"></script>
<script type="text/javascript" src="/vendors/webuploader/webuploader.min.js"></script>
<script type="text/javascript" src="/vendors/webuploader/order_webupload.js"></script>
<script type="text/javascript">
$(function(){
    if ($("select[name='process']").val()==5) {
       $('#service').show();
    } else {
       $('#service').hide();
    }
    //收费类别
    $("select[name='process']").change(function(){
       if ($(this).val()==5) {
          $('#service').show();
       } else {
          $('#service').hide();
       }
    });

    $(".selectpicker").change(function(){

        if($(this).val() < $(this).attr("orgType")){
            alert("订单状态不可逆修改");
            $(this).val($(this).attr("orgType"))
            $(this).stopPropagation()
            return;
        }
        if($(this).val() == '2' ){
            if($("#cover_image").val() ==""){
                $(".col-sm-3").show()
            }else{
                $(".cert_pic").show()
            }
        }else{
            $(".cert_pic").hide()
            $(".col-sm-3").hide()
        }
    })
    setTimeout(function(){
        $(".col-sm-3").hide()
    },1000)
    $(".waves-effect").click(function(){
//        $('form:first .btn-success:last').click(function() {

//            $(this).attr('href', '/order/order_course?'+ url +'&export=1');
//        });
        if($(".selectpicker").val() == '2' && $("#cover_image").val()==''){
            alert("修改支付状态时，需要上传支付凭证!");
            return false;
        }
        var url = "{{route('order.order_opo_show',['id'=>$order->id])}}";
        var param = $(this).closest('form').serialize();
        $.ajax({
            type: 'post',
            url: url,
            data: param ,
            success: function (res) {
                location.href = '{{ route('order.order_opo') }}'
            }
        });
        return;

        return true;
    })
})
</script>
@endsection
