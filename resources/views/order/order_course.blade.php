@extends('layouts.material')
@section('content')
<style>
.btn-select { 
position: relative; 
display: inline-block; 
width: 100px; 
height:22px; 
margin:10px 0px 10px 0px;
}
</style>
    <div class="container">
        <div class="block-header">
            <h2>好课订单列表</h2>
        </div>
        <div class="card">
            <div class="card-header card-padding">
                <form action="{{ route('order.order_course') }}" method='GET'>
                    <div class="row">
                        <div class="col-sm-2">
                            <div class="input-group form-group">
                                <span class="input-group-addon"><i class="zmdi zmdi-caret-down-circle zmdi-hc-fw"></i></span>
                                <div class="dtp-container fg-line">
                                    <div class="select">
                                        <select class="selectpicker" name="promoter">
                                            <option value="">发起人</option>
                                            @foreach ($partner_list as $k=>$item)
                                                <option value="{{$k}}" @if(request('promoter')==$k) selected @endif>{{$item}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-2">
                            <div class="input-group form-group">
                                <span class="input-group-addon"><i class="zmdi zmdi-caret-down-circle zmdi-hc-fw"></i></span>
                                <div class="dtp-container fg-line">
                                    <div class="select">
                                        <select class="selectpicker" name="order_type">
                                            <option value="">付款状态</option>
                                            @foreach ($order_type as $k=>$item)
                                                <option value="{{$k}}" @if(request('order_type')==$k) selected @endif>{{$item}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                         <!--<div class="col-sm-2">
                            <div class="input-group form-group">
                                <span class="input-group-addon"><i class="zmdi zmdi-caret-down-circle zmdi-hc-fw"></i></span>
                                <div class="dtp-container fg-line">
                                    <div class="select">
                                        <select class="selectpicker" name="status">
                                            <option value="">课程状态</option>
                                            @foreach ($status_list as $k=>$item)
                                                <option value="{{$k}}" @if(request('status')==$k) selected @endif>{{$item}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>-->
                        <div class="col-sm-2">
                            <div class="input-group form-group">
                                <span class="input-group-addon"><i class="zmdi zmdi-caret-down-circle zmdi-hc-fw"></i></span>
                                <div class="dtp-container fg-line">
                                    <div class="select">
                                        <select class="selectpicker" name="report_flg">
                                            <option value="">报到状态</option>
                                            @foreach ($report_flg as $k=>$item)
                                                <option value="{{$k}}" @if(request('report_flg')==$k) selected @endif>{{$item}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-2">
                            <div class="input-group form-group">
                                <span class="input-group-addon"><i class="zmdi zmdi-caret-down-circle zmdi-hc-fw"></i></span>
                                <div class="dtp-container fg-line">
                                    <div class="select">
                                        <select class="selectpicker" name="pay_method">
                                            <option value="">支付方式</option>
                                            @foreach ($pay_type as $k=>$item)
                                                <option value="{{$k}}" @if(request('pay_method')==$k) selected @endif>{{$item}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!--<div class="col-sm-2">
                            <div class="input-group form-group">
                                <span class="input-group-addon"><i class="zmdi zmdi-caret-down-circle zmdi-hc-fw"></i></span>
                                <div class="dtp-container fg-line">
                                    <div class="select">
                                        <select class="selectpicker" name="package_type">
                                            <option value="">订单类型</option>
                                            @foreach ($package_type as $k=>$item)
                                                <option value="{{$k}}" @if(request('package_type')==$k) selected @endif>{{$item}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>-->
                        <div class="col-sm-2">
                            <div class="input-group">
                                <span class="input-group-addon"><i class="zmdi zmdi-search"></i></span>
                                <div class="fg-line">
                                    <input type="text" class="form-control" placeholder="课程名称" name='order_name' value="{{ request('order_name') }}">
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-2">
                            <div class="input-group">
                                <span class="input-group-addon"><i class="zmdi zmdi-search"></i></span>
                                <div class="fg-line">
                                    <input type="text" class="form-control" placeholder="订单号" name='order_code' value="{{ request('order_code') }}">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">

                        <div class="col-sm-2">
                            <div class="input-group">
                                <span class="input-group-addon"><i class="zmdi zmdi-account zmdi-hc-fw"></i></span>
                                <div class="fg-line">
                                        <input type="text" class="form-control" placeholder="用户昵称" name='nickname' value="{{ request('nickname') }}">
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-2">                       
                            <div class="input-group">
                                <span class="input-group-addon"><i class="zmdi zmdi-phone zmdi-hc-fw"></i></span>
                                <div class="fg-line">
                                        <input type="text" class="form-control" placeholder="手机号" name='consignee_tel' value="{{ request('consignee_tel') }}">
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-2">
                            <div class="input-group">
                                <span class="input-group-addon"><i class="zmdi zmdi-caret-down-circle zmdi-hc-fw"></i></span>
                                <div class="fg-line">
                                     <select class="btn-select province" name="province" id="province">
                                        <option value="">请选择省</option>
                                        @foreach($province_list as  $provinces)
                                            <option value="{{$provinces['area_id']}}" @if(request('province')== $provinces['area_id']) selected @endif >{{$provinces['area_name']}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                         <div class="col-sm-2">
                            <div class="input-group">
                                <span class="input-group-addon"><i class="zmdi zmdi-caret-down-circle zmdi-hc-fw"></i></span>
                                <div class="fg-line">
                                    <select class="btn-select city" name="city" id="city">
                                    	<option value="">请选择市</option
                                        @foreach($city_list2 as  $citys)
                                            <option value="{{$citys['area_id']}}" @if(request('city') == $citys['area_id']) selected @endif >{{$citys['area_name']}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-2">
                            <div class="input-group">
                                <span class="input-group-addon"><i class="zmdi zmdi-calendar"></i></span>
                                <div class="dtp-container fg-line">
                                    <input type="text" class="date-picker form-control" name='s_time' value="{{ request('s_time') }}" placeholder="时间段-开始">
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-2">
                            <div class="input-group">
                                <span class="input-group-addon"><i class="zmdi zmdi-calendar"></i></span>
                                <div class="dtp-container fg-line">
                                    <input type="text" class="date-picker form-control" name='e_time' value="{{ request('e_time') }}" placeholder="时间段-截止">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row" style="float:right;">
                        <div class="col-sm-2" >                       
                            <div class="input-group" style="min-width: 91px;">
				<a href="#" target="_blank" class="btn btn-success btn-sm waves-effect pull-right">导出</a>
                                <button type="submit" class="btn btn-primary btn-sm  waves-effect">搜索</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="card-body table-responsive" style="clear: both;">
                <table id="data-table-selection" class="table table-striped">
                    <thead>
                    <tr>
                        <th>订单号</th>
                        <th>课程名称</th>
                        {{--<th>课程状态</th>--}}
                        <th>发起人</th>
                        <th>手机号</th>
                        <th>城市</th>
                        <th>用户</th>
                        <th>分享人</th>
                        <th>总价<small class="c-red">(元)</small></th>
                        <th>付款状态</th>
                        <th>报到状态</th>
                        <th>操作</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach ($data as $item)
                        <tr id="b{{$item->id}}">
                            <td>{{ $item->order_code }}</td>
                            <td>{{ @str_limit($item->order_name,30) }}</td>
                            <td>@if($item->user){{ @str_limit($item->user->nickname)}}@endif</td>
                            <!--<td>item->order_course->consignee_tel </td>-->
                            <td>@if($item->user && $item->user->mobile){{ $item->user->mobile }}@endif</td>
                            {{--<td>{{ @$partner_list[$item->course->promoter] }}</td>--}}
                            <td>@if($item->order_course && $item->order_course->user_city){{ $arrArea[$item->order_course->user_city] }}@endif</td>
                            <td>{{ @$item->user->nickname }}</td>
                            <td>{{@$item->lover_course->lover->nickname}} / {{@$item->lover_course->lover->mobile}} </td>
                            <td>@if($item->free_flg=='2')<strong class="c-red">{{ @$item->total_price }}</strong>@else<strong class="c-green">免费</strong>@endif</td>
                            <td>{{ @$order_type[$item->order_type] }}</td> 
                            <td>{{ @$report_flg[$item->order_course->report_flg] }}</td>
                            <td>
                                <a href="{{route('order.order_course_show',['id'=>$item->id])}}" title="详情"><button class="btn bgm-orange waves-effect btn-sm"><i class="zmdi zmdi-eye"></i></button></a>
                                @if($item->order_type == 1)
                                    <button data-id="{{$item->id}}" class="btn btn-info waves-effect sa-warning btn-sm" title="删除"><i class="zmdi zmdi-close"></i></button>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
                {!! $data->render() !!}
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script type="text/javascript">
	$(document).ready(function(){
	$("#province").change(function(){
            if ($('#province').val()==''||$('#province').val()==undefined) {
            	var html_str = '<option value="">请选择市</option>';
            	$(".city").html(html_str);
            }else{
            	$.ajax({
                    type: 'post',
                    url: '{{route('user.getcitylist')}}',
                    data: {id: $('#province').val()},
                    success: function (res) {
                    	var citylist = res.data;
                    	var html_str = '';
                    	for(var i = 0;i<citylist.length;i++){
                    		ol_str = '<option value="'+citylist[i].area_id+'">'+citylist[i].area_name+'</option>';
                    		html_str += ol_str;
                    	}
                    	$(".city").html(html_str);
                    }
                });
            }
        });

            $('form:first .btn-success:last').click(function() {
                var url = $(this).closest('form').serialize();
                $(this).attr('href', '/order/order_course?'+ url +'&export=1');
            });
            
        $('.sa-warning').click(function () {
            var id = $(this).data('id');
            swal({
                title: "确定删除?",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "删除",
                cancelButtonText: "取消",
                closeOnConfirm: false
            }, function () {
                $.ajax({
                    type: 'post',
                    url: '{{route('order.vip_remove')}}',
                    data: {id: id},
                    success: function (res) {
                        if (res.code == 0) {
                            $('#b' + id).remove();
                            swal(res.message, "", "success");
                        } else {
                            swal(res.message);
                        }
                    }
                });
            });
        });
    });
    </script>
@endsection
