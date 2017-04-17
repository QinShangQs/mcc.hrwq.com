@extends('layouts.material')
@section('content')
    <div class="container">
        <div class="block-header">
            <h2><a href="{{route('coupon_rule')}}">获取规则</a>  -> 详情</h2>
        </div>
        <div class="card">
            <div class="card-body card-padding">
                    <div class="row">
                        <div class="col-sm-4">
                            <div class="form-group fg-line ">
                                <label>名称</label>
                                <div class="fg-line form-group c-gray">
                                    {{$coupon_rule->name}}
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-2">
                            <div class="form-group fg-line ">
                                <label>获取规则</label>
                                <div class="fg-line form-group c-gray">
                                  {{$coupon_get_rule[$coupon_rule->rule_id]}}
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-2">
                            <div class="form-group fg-line ">
                                <label>优惠券模板</label>
                                @foreach($coupon_rule->coupons as $item)
                                    <div class="fg-line form-group c-gray">
                                        {{$coupon[$item->id]}}
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                @if($coupon_rule->rule_id == 1)
                    <div class="row">
                        <div class="col-sm-2">
                            <div class="form-group fg-line ">
                                <label>邀请人可领取红包的所属</label>
                                <div class="fg-line form-group c-gray">
                                    {{$agency[$coupon_rule->agency_id]}}
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-2">
                            <div class="form-group fg-line ">
                                <label>领取的红包金额<small>(单位：元)</small></label>
                                <div class="fg-line form-group c-gray">
                                    {{$coupon_rule->bouns}}
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
