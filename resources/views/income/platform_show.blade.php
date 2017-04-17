@extends('layouts.material')
@section('content')
    <div class="container">
        <div class="block-header">
            <h2><a href="{{route('income.platform')}}">平台收益</a> -> 详情</h2>
        </div>
        <div class="card">
            <div class="card-body card-padding ">
                    <div class="row">
                        <div class="col-sm-3">
                            <div class="form-group fg-line ">
                                <label>时间</label>
                                <div class="fg-line form-group c-gray">
                                    {{$income->created_at}}
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-3">
                            <div class="form-group fg-line">
                                <label>类型</label>
                                <div class="fg-line form-group @if($income->log_type == 1) c-red @else c-green @endif">
                                       {{ $income_log_type[$income->log_type]}}
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-3">
                            <div class="form-group fg-line ">
                                <label>金额</label>
                                <div class="fg-line form-group f-500  @if($income->log_type == 1) c-red @else c-green @endif">
                                    @if($income->log_type == 1)
                                          +{{ $income->amount}}
                                    @else
                                          -{{$income->amount}}
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-3">
                            <div class="form-group fg-line ">
                                <label>平台余额</label>
                                <div class="fg-line form-group c-blue f-500">
                                    <strong>{{ $income->total_amount}}</strong>
                                </div>
                            </div>
                        </div>

                    </div>

                    <div class="row">
                        <div class="col-sm-3">
                            <div class="form-group fg-line ">
                                <label>支付方式</label>
                                <div class="fg-line form-group c-gray">
                                    {{ $income_pay_type[$income->pay_mod]}}
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-group fg-line ">
                                <label>来源</label>
                                <div class="fg-line form-group c-gray">
                                    {{  $income->income_type == 0 ? '无' :$income_in_type[$income->income_type]}}
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-6">
                            <div class="form-group fg-line ">
                                <label>说明</label>
                                <div class="fg-line form-group c-gray">
                                    {{$income->remark}}
                                </div>
                            </div>
                        </div>

                    </div>
            </div>
            <hr>
            @if(isset($income->user))
            <table id="data-table-selection" class="table table-striped ">
                <thead>
                <tr>
                    <th>所属用户</th>
                    <th>手机号</th>
                    <th>地区</th>
                    <th>用户积分</th>
                    <th>成长值</th>
                    <th>注册时间</th>
                </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><a>{{ $income->user->nickname }}</a></td>
                        <td>{{ $income->user->mobile }}</td>
                        <td>{{ get_area_name($income->user->province)}} -  {{get_area_name($income->user->city)}}</td>
                        <td>{{ $income->user->grow }}</td>
                        <td>{{ $income->user->score }}</td>
                        <td>{{ $income->user->created_at }}</td>
                    </tr>
                </tbody>
            </table>
            @endif

            @if(isset($income->order))
            <table id="data-table-selection" class="table table-striped ">
                <thead>
                <tr>
                    <th>所属订单</th>
                    <th>所属类别</th>
                    <th>物品名称</th>
                    <th>订单金额</th>
                    <th>下单时间</th>
                    <th>支付时间</th>
                </tr>
                </thead>
                <br>  <br>
                <tbody>
                <tr>
                    <td><a>{{ $income->order->order_code }}</a></td>
                    <td>{{ $order_belong_category[$income->order->pay_type] }}</td>
                    <td>{{ $income->order->order_name }}</td>
                    <td>{{ $income->order->price }}</td>
                    <td>{{ $income->order->created_at }}</td>
                    <td>{{ $income->order->pay_time }}</td>
                </tr>
                </tbody>
            </table>
            @endif

        </div>
        </div>
    </div>
@endsection


