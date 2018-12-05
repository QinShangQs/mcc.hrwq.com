@extends('layouts.material')
@section('content')
<div class="container">
    <div class="block-header">
        <h2><a href="{{route('user.index')}}">用户信息管理</a> -> 会员动态</h2>
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
                            
                            {{ $user->getPartnerCityName() }}
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
                
                <div class="col-sm-2">
                    <div class="form-group fg-line ">
                        <label>是否为和会员</label>
                        <div class="fg-line form-group c-gray">
                            <strong> @if($user->vip_flg){{ $user_vip_flg[$user->vip_flg] }}@endif @if(is_vip_forever($user->vip_forever)) 长期 @endif</strong>
                        </div>
                    </div>
                </div>
                
                <div class="col-sm-2">
                    <div class="form-group fg-line ">
                        <label>剩余天数</label>
                        <div class="fg-line form-group c-green">
                            <strong> 
                                {{vip_left_day_text($user->vip_forever,$user->vip_left_day)}}
                                @if(is_vip_forever($user->vip_forever) == false)
                                    天
                                @endif
                                    
                            </strong>
                        </div>
                    </div>
                </div>
                
                
            </div>
        </div>
        <table id="data-table-selection" class="table table-striped ">
            <thead>
                <tr>
                    <th>动态 : 时间</th>
                    <th>增加天数</th>
                    <th>来源</th>
                    <th>备注</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($user->user_point_vip as $item)
                <tr>
                    <td>{{ $item->created_at}}</td>

                    <td class="c-red">
                        +{{ $item->point_value }}
                    </td>
                    <td>
                        {{ config('constants.vip_point_source')[$item->source]}}
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


