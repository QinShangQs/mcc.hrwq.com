@extends('layouts.material')
@section('content')
    <div class="container">
        <div class="block-header">
            <h2>和会员订单列表</h2>
        </div>
        <div class="card">
            <div class="card-header card-padding">
                <form action="{{ route('order.order_vip') }}" method='GET'>
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
                                        <select class="selectpicker" name="has_lover">
                                            <option @if(request('has_lover')=="") selected @endif value="">是否关联爱心大使</option>
                                            <option @if(request('has_lover')=="1") selected @endif  value="1">是</option>
                                            <option @if(request('has_lover')=="2") selected @endif  value="2">否</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-2">
                            <div class="input-group">
                                <span class="input-group-addon"><i class="zmdi zmdi-account zmdi-hc-fw"></i></span>
                                <div class="fg-line">
                                        <input type="text" class="form-control" placeholder="爱心大使手机号/姓名" name='lover_key' value="{{ request('lover_key') }}">
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
                        <div class="col-sm-2">
                            <div class="text-right form-group">
                                <button type="submit" class="btn btn-primary btn-sm  waves-effect">搜索</button>
                                <a href="#" target="_blank" class="btn btn-success btn-sm waves-effect">导出</a>
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
                        <th>用户</th>
                        <th>手机号</th>
                        <th>总价<small class="c-red">(元)</small></th>
                        <th>付款状态</th>
                        <th>支付时间</th>
                        <th>爱心大使手机号/姓名</th>
                        <th>操作</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach ($data as $item)
                        <tr id="b{{$item->id}}">
                            <td>{{ $item->order_code }}</td>
                            <td>{{ @$item->user->nickname }}</td>
                            <td>{{ @$item->user->mobile }}</td>
                            <td><strong class="c-red">{{ @$item->total_price }}</strong></td>
                            <td>{{ @$order_type[$item->order_type] }}</td>
                            <td>{{ $item->pay_time }}</td> 
                            <td>
                            	@if($item->lover)
                            		{{ $item->lover->mobile }}/{{ $item->lover->nickname }}
                            	@endif
                            </td> 
                            <td>
                                <a href="{{route('order.order_vip_show',['id'=>$item->id])}}" title="详情"><button class="btn bgm-orange waves-effect"><i class="zmdi zmdi-eye"></i></button></a>
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
        $(function() {
            $('form:first .btn-success:last').click(function() {
                var url = $(this).closest('form').serialize();
                $(this).attr('href', '/order/order_vip?'+ url +'&export=1');
            });
        });
    </script>
@endsection
