@extends('layouts.material')
@section('content')
    <div class="container">
        <div class="block-header">
            <h2><a href="{{route('income.cash')}}">提现申请</a> -> 详情</h2>
        </div>
        <div class="card">
            <div class="card-body card-padding ">
                    <div class="row">
                        <div class="col-sm-2">
                            <div class="form-group fg-line ">
                                <label>手机号</label>
                                <div class="fg-line form-group c-gray">
                                    {{$data->user->mobile}}
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-2">
                            <div class="form-group fg-line ">
                                <label>昵称</label>
                                <div class="fg-line form-group c-gray">
                                    {{$data->user->nickname}}
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-2">
                            <div class="form-group fg-line ">
                                <label>角色</label>
                                <div class="fg-line form-group c-gray">
                                    {{ $user_role[$data->user->role]}}
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-2">
                            <div class="form-group fg-line ">
                                <label>地区</label>
                                <div class="fg-line form-group c-gray">
                                @if($data->user->province == null)
                                暂无
                                @else
                                    {{ $data->user->c_province->area_name}} - {{$data->user->c_city->area_name}}
                                @endif
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-2">
                            <div class="form-group fg-line ">
                                <label>累计收益</label>
                                <div class="fg-line form-group c-red">
                                    <strong> {{$data->user->balance}}</strong>
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-2">
                            <div class="form-group fg-line ">
                                <label>当前余额</label>
                                <div class="fg-line form-group c-red">
                                    <strong> {{$data->user->current_balance}}</strong>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-2">
                            <div class="form-group fg-line ">
                                <label>注册时间</label>
                                <div class="fg-line form-group c-gray">
                                    <strong> {{$data->user->created_at}}</strong>
                                </div>
                            </div>
                        </div>
                    </div>

            </div>
            <hr>
            <table id="data-table-selection" class="table table-striped ">
                <thead>
                <tr>
                    <th>提现金额</th>
                    <th>申请时间</th>
                    <th>转账时间</th>
                    <th>状态</th>
                    @if($data->apply_status == 2)
                        <th>驳回原因</th>
                    @endif
                    <th></th>
                </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>{{ $data->cash_amount}}</td>
                        <td>{{ $data->created_at}}</td>
                        <td>{{ $data->updated_at}}</td>
                        <td>{{$income_cash_state[ $data->apply_status]}}</td>

                        @if($data->apply_status == 2)
                            <td>{{ $data->refuse_reason}}</td>
                        @endif
                    </tr>
                </tbody>
            </table>
        </div>
        </div>
    </div>
@endsection


