@extends('layouts.material')
@section('content')
    <div class="container">
        <div class="block-header">
            <h2><a href="{{route('income.point')}}">积分管理</a> -> 积分记录</h2>
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
                                <label>当前积分值</label>
                                <div class="fg-line form-group c-red">
                                   <strong> {{$user->score}}</strong>
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

                    </div>
            </div>
            <hr>
            <table id="data-table-selection" class="table table-striped ">
                <thead>
                <tr>
                    <th>流水账 ： 积分值</th>
                    <th>时间</th>
                    <th>来源</th>
                    <th>备注</th>
                </tr>
                </thead>
                <tbody>
                @foreach ($user->user_point as $item)
                    <tr>
                        @if($item->move_way == 1)
                            <td class="c-red">
                               +{{ $item->point_value }}
                            </td>
                        @else
                            <td  class="c-green">
                                －{{ $item->point_value }}
                            </td>
                        @endif

                        <td>{{ $item->created_at}}</td>
                        <td>
                            {{ $income_point_source[$item->source]}}
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


