@extends('layouts.material')
@section('content')
<div class="container">
    <div class="block-header">
        <h2><a href="{{route('order.order_vcourse')}}">好看订单</a>  -> 好看订单详情</h2>
    </div>
    <div class="card">
        <div class="card-body card-padding">
            <form id="post_form" action="{{route('order.order_vcourse_show',['id'=>$order->id])}}" method="post">
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
            </div>
            <div class="row">
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
                        <label>付款状态</label>
                        <select class="selectpicker" size="10"  name="order_type" disabled="disabled">
                            @foreach ($order_type as $key=>$item)
                                <option value="{{$key}}" @if($order->order_type==$key) selected @endif>{{$item}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-sm-2">
                    <div class="form-group fg-line ">
                        <label>付款时间</label>
                        <input type="text" value="{{@$order->pay_time}}" name="pay_time"  class="form-control input-sm" disabled="disabled">
                    </div>
                </div>
            </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('script')
<script type="text/javascript">
</script>
@endsection
