@extends('layouts.material')
@section('content')
    <div class="container">
        <div class="block-header">
            <h2><a href="{{route('income.user')}}">用户余额</a> -> 卡片账</h2>
        </div>
        <div class="card">
            <div class="card-body card-padding ">
                    <div class="row">
                        <div class="col-sm-2">
                            <div class="form-group fg-line ">
                                <label>手机号</label>
                                <div class="fg-line form-group c-gray">
                                    {{$user->mobile}}
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-2">
                            <div class="form-group fg-line ">
                                <label>昵称</label>
                                <div class="fg-line form-group c-gray">
                                    {{$user->nickname}}
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-2">
                            <div class="form-group fg-line ">
                                <label>角色</label>
                                <div class="fg-line form-group c-gray">
                                    {{ $user_role[$user->role]}}
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-2">
                            <div class="form-group fg-line ">
                                <label>地区</label>
                                <div class="fg-line form-group c-gray">
                                    {{ @$user->c_province->area_name}} - {{@$user->c_city->area_name}}
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-2">
                            <div class="form-group fg-line ">
                                <label>当前余额</label>
                                <div class="fg-line form-group c-red">
                                   <strong> {{$user->current_balance}}</strong>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-2">
                            <div class="form-group fg-line ">
                                <label>总收益</label>
                                <div class="fg-line form-group c-green">
                                    <strong> {{$user->balance}}</strong>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-2">
                            <div class="form-group fg-line ">
                                <label>累计提现</label>
                                <div class="fg-line form-group c-teal">
                                    <strong> {{$user->cash_amount}}</strong>
                                </div>
                            </div>
                        </div>


                        <div class="col-sm-2">
                            <div class="form-group fg-line ">
                                <label>注册时间</label>
                                <div class="fg-line form-group c-gray">
                                    <strong> {{$user->created_at}}</strong>
                                </div>
                            </div>
                        </div>
                    </div>
            </div>
            <table id="data-table-selection" class="table table-striped ">
                <thead>
                <tr>
                    <th>卡片账 : 时间</th>
                    <th>金额</th>
                    <th>类型</th>
                    <th>来源</th>
                    <th>备注</th>
                </tr>
                </thead>
                <tbody>
                @foreach ($user->user_balance as $item)
                    <tr>

                        <td>{{ $item->created_at}}</td>

                        @if($item->operate_type == 1)
                            <td class="c-red">
                               +{{ $item->amount }}
                            </td>
                        @else
                            <td  class="c-green">
                                －{{ $item->amount }}
                            </td>
                        @endif

                        <td>
                            {{config('constants.user_balance_type')[$item->operate_type]}}
                        </td>

                        <td>
                            {{ config('constants.user_balance_source')[$item->source]}}
                        </td>
                        <td>{{ $item->remark}}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
        </div>
    </div>
@endsection


