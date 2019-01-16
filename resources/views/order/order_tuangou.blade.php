@extends('layouts.material')
@section('content')
<div class="container">
    <div class="block-header">
        <h2>团购订单列表</h2>
    </div>
    <div class="card">
        <div class="card-header card-padding">
            <form action="{{ route('order.order_tuangou') }}" method='GET'>
                <div class="row">

                    <div class="col-sm-2">
                        <div class="input-group">
                            <span class="input-group-addon"><i class="zmdi zmdi-account zmdi-hc-fw"></i></span>
                            <div class="fg-line">
                                <input type="text" class="form-control" placeholder="用户" name='nickname' value="{{ request('nickname') }}">
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
                            <span class="input-group-addon"><i class="zmdi zmdi-search"></i></span>
                            <div class="fg-line">
                                <input type="text" class="form-control" placeholder="订单号" name='order_code' value="{{ request('order_code') }}">
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
                    
                    <div class="col-sm-2">
                        <div class="text-right form-group">
                            <button type="submit" class="btn btn-primary btn-sm  waves-effect">搜索</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
        <div class="card-body table-responsive">
            <table id="data-table-selection" class="table table-striped">
                <thead>
                    <tr>
                        <th>订单号</th>
                        <th>团购号</th>
                        <th>用户</th>
                        <th>城市</th>
                        <th>手机号</th>
                        <th>团购价<small class="c-red">(元)</small></th>
                        <th>实际付款<small class="c-red">(元)</small></th>
                        <th>付款状态</th>
                        <th>支付时间</th>
                        <th>团购状态</th>
                        <th>截团人数/时间</th>
                        <th>操作</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($data as $item)
                    <tr id="b{{@$item->order->id}}">
                        <td>{{ @$item->order->order_code }}</td>
                        <td>{{ @$item->order_team_id }}</td>
                        <td>
                            {{ @$item->user->nickname }}
                            <span class="label-info">
                            @if($item->member_type == 1)
                                发起人
                            @else
                                参与人
                            @endif
                            </span>
                        </td>
                        <td>@if(@$item->user->province){{$areas[$item->user->province]}}@endif @if($item->user->city){{$areas[$item->user->city]}}@endif</td>
                        <td>{{ @$item->user->mobile }}</td>
                        <td><strong class="c-red">{{ @$item->team->price }}</strong></td>
                        <td><strong class="c-red">{{ @@$item->order->price }}</strong></td>
                        <td>{{ @$order_type[@$item->order->order_type] }}</td>
                        <td>{{ @$item->order->pay_time }}</td> 
                        <td>{{ @$tuan_status[@$item->team->status] }}</td>
                        <td>{{ @$item->team->need_members_cnt }}人 / {{ @$item->team->ended_at }}</td>
                        <td>
                            <a href="{{route('order.order_course_show',['id'=>@$item->order->id])}}" title="详情"><button class="btn bgm-orange waves-effect"><i class="zmdi zmdi-eye"></i></button></a>
                            @if(@$item->order->order_type == 1)
                            <button data-id="{{@$item->order->id}}" class="btn btn-info waves-effect sa-warning" title="删除"><i class="zmdi zmdi-close"></i></button>
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
<!-- 获取城市 -->
<script type="text/javascript">
//    function getcity(obj) {
//        var city = "<select  class='selectpicker' id='search_city' name='search_city'>";
//        var arrcity = new Array();
//        arrcity = <?php /*print_r($arrareaCs);*/ ?>;
//        arrcity = arrcity[obj];
//        console.log(arrcity);
//        for (var i in arrcity) {
//            city += "<option value='" + arrcity[i]['area_id'] + "'>" + arrcity[i]['area_name'] + "</option>";
//        }
//        city += "</select>";
//        console.log(city);
//        document.getElementById("areaC").innerHTML = city;
//        $('#search_city').selectpicker();
//    }

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
</script>
<script type="text/javascript">
    $(function () {
        $('form:first .btn-success:last').click(function () {
            var url = $(this).closest('form').serialize();
            $(this).attr('href', '/order/order_vip?' + url + '&export=1');
        });
    });
</script>
@endsection
