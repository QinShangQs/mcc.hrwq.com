@extends('layouts.material')
@section('content')
    <div class="container">
        <div class="block-header">
            <h2>获取规则</h2>
        </div>
        <div class="card">
                <div class="card-header card-padding">
                    <a href="{{route('coupon_rule.create')}}">
                        <button class="btn btn-success btn-sm  waves-effect ">
                            <i class="zmdi zmdi-plus zmdi-hc-fw"></i>添加获取规则
                        </button>
                    </a>
                </div>

                <div class="table-responsive">
                    <table id="data-table-selection" class="table table-striped">
                        <thead>
                        <tr>
                            <th>名称</th>
                            <th>获取规则</th>
                            <th>优惠券模板</th>
                            <th>类型</th>
                            <th>优惠</th>
                            <th>使用范围</th>
                            <th>有效期</th>
                            <th>规则开关</th>
                            <th>操作</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($coupon_rules as $item)
                            @foreach($item->coupons as $key=>$coupon)
                                <tr>
                                    @if($key==0)
                                        <td rowspan="{{count($item->coupons)}}">{{$item->name}}</td>
                                        <td rowspan="{{count($item->coupons)}}"><button class="btn bgm-cyan waves-effect">{{$coupon_get_rule[$item->rule_id] }}</button></td>
                                    @endif
                                    <td><a href="{{route('coupon.show',['id'=>$coupon->id])}}">{{$coupon->name}}</a></td>
                                    <td><button class="btn  @if($coupon->type==1) bgm-deeporange  @else  bgm-green  @endif  waves-effect">{{ $type[$coupon->type]}}</button></td>
                                    <td>
                                        @if($coupon->type == 1)
                                            满{{$coupon->full_money}}元 减{{$coupon->cut_money}}元
                                        @else
                                            {{$coupon->discount}}折
                                        @endif
                                    </td>
                                    <td>{{$coupon_use_scope[$coupon->use_scope]}}</td>
                                    <td>
                                        @if($coupon->available_period_type == 1)
                                            {{$coupon->available_days}}天
                                        @else
                                            {{$coupon->available_start_time}} 至 {{$coupon->available_end_time}}
                                        @endif
                                    </td>
                                    @if($key==0)
                                        <td rowspan="{{count($item->coupons)}}">
                                            <div class="toggle-switch" data-ts-color="blue">
                                                <input id="ts{{$item->id}}" type="checkbox" hidden="hidden"
                                                       value="{{$item->id}}"
                                                       @if(empty($item->deleted_at)) checked @endif >
                                                <label for="ts{{$item->id}}" class="ts-helper"></label>
                                            </div>
                                        </td>
                                        <td rowspan="{{count($item->coupons)}}">
                                            <a href="{{route('coupon_rule.show',['id'=>$item->id])}}">
                                                <button class="btn  bgm-orange waves-effect"><i
                                                            class="zmdi zmdi-eye"></i></button>
                                            </a>
                                            <a href="{{route('coupon_rule.edit',['id'=>$item->id])}}">
                                                <button class="btn  bgm-orange waves-effect"><i
                                                            class="zmdi zmdi-edit"></i></button>
                                            </a>
                                        </td>
                                    @endif
                                </tr>
                            @endforeach
                        @endforeach
                        </tbody>
                    </table>
                    {!! $coupon_rules->render() !!}
                </div>
            </div>
        </div>
@endsection


@section('script')
    <script type="text/javascript">
        $(document).ready(function(){
            $('body').on('change', '#data-table-selection input:checkbox', function () {
                if ($(this).is(':checked')) {
                    $.ajax({
                        type: 'post',
                        url: '{{route('coupon_rule.restore')}}',
                        data: {id:$(this).val()},
                        dataType: 'json',
                        success: function (res) {
                            if(res.code == 0){
//                                swal(res.message);
                            } else {
                                swal(res.message);
                            }
                        },
                        error: function (res) {
                            var errors = res.responseJSON;
                            for (var o in errors) {
                                swal({
                                    title: errors[o][0],
                                    type: "error"
                                });
                                break;
                            }
                        }
                    });
                }
                else {
                    $.ajax({
                        type: 'post',
                        url: '{{route('coupon_rule.delete')}}',
                        data: {id:$(this).val()},
                        dataType: 'json',
                        success: function (res) {
                            if(res.code == 0){
//                                swal(res.message);
                            } else {
                                swal(res.message);
                            }
                        },
                        error: function (res) {
                            var errors = res.responseJSON;
                            for (var o in errors) {
                                swal({
                                    title: errors[o][0],
                                    type: "error"
                                });
                                break;
                            }
                        }
                    });
                }
            });
        });

    </script>
@endsection