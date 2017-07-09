@extends('layouts.material')
@section('content')
    <div class="container">
        <div class="block-header">
            <h2><a href="{{route('order.order_vcourse')}}">和会员订单</a> -> 和会员订单详情</h2>
        </div>
        <div class="card">
            <div class="card-body card-padding">
                <form id="post_form" action="{{route('order.order_vip_show',['id'=>$order->id])}}" method="post">
                    {!! csrf_field() !!}
                    <div class="row">
                        <div class="col-sm-2">
                            <div class="form-group fg-line ">
                                <label>订单号</label>
                                <input type="text" value="{{$order->order_code}}" name="order_code"
                                       class="form-control input-sm" disabled="disabled">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-2">
                            <div class="form-group fg-line ">
                                <label>用户</label>
                                <input type="text" value="{{@$order->user->nickname}}" name="order_name"
                                       class="form-control input-sm" disabled="disabled">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-2">
                            <div class="form-group fg-line ">
                                <label>下单时间</label>
                                <input type="text" value="{{@$order->created_at}}" name="created_at"
                                       class="form-control input-sm" disabled="disabled">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-2">
                            <div class="form-group fg-line ">
                                <label>支付方式</label>
                                <input type="text" value="{{@$pay_type[$order->pay_method]}}" name="pay_method"
                                       class="form-control input-sm" disabled="disabled">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-2">
                            <div class="form-group fg-line ">
                                <label>总价</label>
                                <input type="text" value="{{@$order->total_price}}" name="total_price"
                                       class="form-control input-sm" disabled="disabled">
                            </div>
                        </div>
                        <div class="col-sm-2">
                            <div class="form-group fg-line ">
                                <label>积分减免金额</label>
                                <input type="text" value="{{@$order->point_price}}" name="point_price"
                                       class="form-control input-sm" disabled="disabled">
                            </div>
                        </div>
                        <div class="col-sm-2">
                            <div class="form-group fg-line ">
                                <label>优惠券减免金额</label>
                                <input type="text" value="{{@$order->coupon_price}}" name="coupon_price"
                                       class="form-control input-sm" disabled="disabled">
                            </div>
                        </div>
                        <div class="col-sm-2">
                            <div class="form-group fg-line ">
                                <label>余额减免金额</label>
                                <input type="text" value="{{@$order->balance_price}}" name="balance_price"
                                       class="form-control input-sm" disabled="disabled">
                            </div>
                        </div>
                        <div class="col-sm-2">
                            <div class="form-group fg-line ">
                                <label>实际支付金额</label>
                                <input type="text" value="{{@$order->price}}" name="price" class="form-control input-sm"
                                       disabled="disabled">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-2">
                            <div class="form-group fg-line ">
                                <label>收货人</label>
                                <input type="text" value="{{@$order->order_vip->consignee}}" name="consignee"  class="form-control input-sm" disabled="disabled">
                            </div>
                        </div>
                        <div class="col-sm-2">
                            <div class="form-group fg-line ">
                                <label>收货人电话</label>
                                <input type="text" value="{{@$order->order_vip->consignee_tel}}" name="consignee_tel"  class="form-control input-sm" disabled="disabled">
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group fg-line ">
                                <label>收货人地址</label>
                                <input type="text" value="{{@$order->order_vip->consignee_address}}" name="consignee_address"  class="form-control input-sm" disabled="disabled">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-2">
                            <div class="form-group fg-line ">
                                <label>爱心大使电话</label>
                                <input type="text" value="{{@$order->lover->mobile}}"  name="consignee"  class="form-control input-sm" disabled="disabled">
                            </div>
                        </div>
                        <div class="col-sm-2">
                            <div class="form-group fg-line ">
                                <label>爱心大使姓名</label>
                                <input type="text" value="{{@$order->lover->nickname}}"  class="form-control input-sm" disabled="disabled">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-2">
                            <div class="form-group fg-line ">
                                <label>付款状态</label>
                                <select class="selectpicker" size="10" name="order_type" disabled="disabled">
                                    @foreach ($order_type as $key=>$item)
                                        <option value="{{$key}}"
                                                @if($order->order_type==$key) selected @endif>{{$item}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-2">
                            <div class="form-group fg-line ">
                                <label>付款时间</label>
                                <input type="text" value="{{@$order->pay_time}}" name="pay_time"
                                       class="form-control input-sm date-time-picker" disabled="disabled">
                            </div>
                        </div>
                    </div>
                    <div class="row" id="delivery">
                        <div class="col-sm-2">
                            <div class="form-group fg-line ">
                                <label>物流公司编号</label>
                                <select class="selectpicker" size="10"  name="delivery_com" >
                                    <option value="">请选择</option>
                                    @foreach ($express_list as $key=>$item)
                                        <option value="{{$key}}" @if(@$order->order_vip->delivery_com==$key) selected @endif>{{$item}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-2">
                            <div class="form-group fg-line ">
                                <label>物流单号</label>
                                <input type="text" value="{{@$order->order_vip->delivery_nu}}" name="delivery_nu"  class="form-control input-sm" >
                            </div>
                        </div>
                    </div>
                    <div class="form-group fg-line" id="submit">
                        <button class="btn bgm-cyan waves-effect" >保存</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('script')
<script type="text/javascript">
$(function(){
    if ($("select[name='order_type']").val()==2||$("select[name='order_type']").val()==4) {
       $('#delivery').show();
       $('#submit').show();
    } else {
       $('#delivery').hide();
       $('#submit').hide();
    }
    //收费类别
    $("select[name='order_type']").change(function(){
       if ($(this).val()==2||$(this).val()==4) {
          $('#delivery').show();
          $('#submit').show();
       } else {
          $('#delivery').hide();
          $('#submit').hide();
       }
    });
})
</script>
@endsection
