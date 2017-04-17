@extends('layouts.material')
@section('style')
    <link href="/vendors/webuploader/webuploader.css" rel="stylesheet">
@endsection
@section('content')
<div class="container">
    <div class="block-header">
        <h2><a href="{{route('order.order_course')}}">好课订单</a>  -> 好课订单详情</h2>
    </div>
    <div class="card">
        <div class="card-body card-padding">
            <form id="post_form" action="{{route('order.order_course_show',['id'=>$order->id])}}" method="post">
            {!! csrf_field() !!}
            <div class="row">
                <div class="col-sm-2">
                    <div class="form-group fg-line ">
                        <label>订单号</label>
                        <input type="text" value="{{$order->order_code}}" name="order_code"  class="form-control input-sm" disabled="disabled">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-6">
                    <div class="form-group fg-line ">
                        <label>课程名称</label>
                        <input type="text" value="{{@$order->order_name}}" name="order_name"  class="form-control input-sm" disabled="disabled">
                    </div>
                </div>
                <div class="col-sm-2">
                    <div class="form-group fg-line ">
                        <label>课程状态</label>
                        <input type="text" value="{{@$status_list[$order->course->status]}}" name="title"  class="form-control input-sm" disabled="disabled">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-2">
                    <div class="form-group fg-line ">
                        <label>发起人</label>
                        <input type="text" value="{{@$partner_list[$order->course->promoter]}}" name="promoter"  class="form-control input-sm" disabled="disabled">
                    </div>
                </div>
                <div class="col-sm-2">
                    <div class="form-group fg-line ">
                        <label>城市</label>
                         <input type="text" value="@if($order->order_course->user_city){{ $arrArea[$order->order_course->user_city] }}@endif" name="area_name"  class="form-control input-sm" disabled="disabled">
                    </div>
                </div>
                <div class="col-sm-2">
                    <div class="form-group fg-line ">
                        <label>用户</label>
                        <input type="text" value="{{@$order->user->nickname}}" name="nickname"  class="form-control input-sm" disabled="disabled">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-2">
                    <div class="form-group fg-line ">
                        <label>订单类型</label>
                        <input type="text" value="{{@$package_type[$order->order_course->package_flg]}}" name="package_flgs"  class="form-control input-sm" disabled="disabled">
                    </div>
                </div>
                <div class="col-sm-2">
                    <div class="form-group fg-line ">
                        <label>下单时间</label>
                        <input type="text" value="{{@$order->created_at}}" name="created_at"  class="form-control input-sm" disabled="disabled">
                    </div>
                </div>
                <div class="col-sm-2">
                    <div class="form-group fg-line ">
                        <label>支付方式</label>
                        <input type="text" value="{{@$pay_type[$order->pay_method]}}" name="pay_method"  class="form-control input-sm" disabled="disabled">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-1">
                    <div class="form-group fg-line ">
                        <label>单价</label>
                        <input type="text" value="{{@$order->each_price}}" name="each_price"  class="form-control input-sm" disabled="disabled">
                    </div>
                </div>
                <div class="col-sm-1">
                    <div class="form-group fg-line ">
                        <label>数量</label>
                        <input type="text" value="{{@$order->quantity}}" name="quantity"  class="form-control input-sm" disabled="disabled">
                    </div>
                </div>
                <div class="col-sm-2">
                    <div class="form-group fg-line ">
                        <label>总价</label>
                        <input type="text" value="@if($order->free_flg=='2'){{@$order->total_price}}@else免费@endif" name="total_price"  class="form-control input-sm" disabled="disabled">
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
                        <label>报到状态</label>
                        <input type="text" value="{{@$report_flg[$order->order_course->report_flg]}}" name="report_flg"  class="form-control input-sm" disabled="disabled">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-2">
                    <div class="form-group fg-line ">
                        <label>付款状态<small class="c-red">(可修改)</small></label>
                        <select class="selectpicker" size="10"  name="order_type" orgType="{{$order->order_type}}" >
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
            <div class="form-group fg-line">
                 <a class="btn bgm-cyan waves-effect" >保存</a>
            </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('script')
    <script type="text/javascript" src="/vendors/webuploader/webuploader.min.js"></script>
    <script type="text/javascript" src="/vendors/webuploader/order_webupload.js"></script>
<script type="text/javascript">
    $(function () {
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
            var url = "{{route('order.order_course_show',['id'=>$order->id])}}";
            var param = $(this).closest('form').serialize();
            $.ajax({
                type: 'post',
                url: url,
                data: param ,
                success: function (res) {
                    location.href = '{{ route('order.order_course') }}'
                }
            });
            return;

            return true;
        })
//        $("#post_form").submit(function(){

//            if($(".selectpicker").val() == '2' && $("#cover_image").val()==''){
//                alert("修改支付状态时，需要上传支付凭证!")
//                return false;
//            }
//            return true;
//        })
    })


</script>
@endsection
