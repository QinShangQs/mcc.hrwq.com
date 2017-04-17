@extends('layouts.material')
@section('content')
    <div class="container">
        <div class="block-header">
            <h2><a href="{{route('coupon')}}">优惠券模板</a>  -> 详情</h2>
        </div>
        <div class="card">
            <div class="card-body card-padding">
                    <div class="row">
                        <div class="col-sm-2">
                            <div class="form-group fg-line ">
                                <label>模板名称</label>
                                <div class="fg-line form-group c-red">
                                    {{ $coupon->name}}
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-2">
                            <div class="form-group fg-line ">
                                <label>类型</label>
                                <div class="fg-line form-group c-gray">
                                    {{ $type[$coupon->type]}}
                                </div>
                            </div>
                        </div>
                        @if($coupon->type == 1)
                        <div class="col-sm-2">
                            <div class="form-group fg-line ">
                                <label>满多少<small>(单位：元)</small></label>
                                <div class="fg-line form-group c-gray">
                                    {{ $coupon->full_money}}
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-2">
                            <div class="form-group fg-line ">
                                <label>减多少<small>(单位：元)</small></label>
                                <div class="fg-line form-group c-gray">
                                    {{ $coupon->cut_money}}
                                </div>
                            </div>
                        </div>
                        @else
                        <div class="col-sm-2">
                            <div class="form-group fg-line ">
                                <label>折扣<small>(如:95表示95折)</small></label>
                                <div class="fg-line form-group c-gray">
                                    {{ $coupon->discount}}
                                </div>
                            </div>
                        </div>
                        @endif

                        <div class="col-sm-2">
                            <div class="form-group fg-line ">
                                <label>适用范围</label>
                                <div class="fg-line form-group c-gray">
                                    {{ $use_scope[$coupon->use_scope]}}
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-2">
                            <div class="form-group fg-line ">
                                <label>有效期类型 </label>
                                <div class="fg-line form-group c-gray">
                                    {{ $coupon_period_type[$coupon->available_period_type]}}
                                </div>
                            </div>
                        </div>

                        @if($coupon->available_period_type == 1)
                            <div class="col-sm-2">
                                <div class="form-group fg-line ">
                                    <label>有效期天数 <small>(单位：天)</small></label>
                                    <div class="fg-line form-group c-gray">
                                        {{ $coupon->available_days}}
                                    </div>
                                </div>
                            </div>
                        @else
                            <div class="col-sm-2">
                                <div class="form-group fg-line ">
                                    <label>有效期开始时间</label>
                                    <div class="fg-line form-group c-gray">
                                        {{ $coupon->available_start_time}}
                                    </div>
                                </div>
                            </div>

                            <div class="col-sm-2">
                                <div class="form-group fg-line ">
                                    <label>有效期结束时间</label>
                                    <div class="fg-line form-group c-gray">
                                     {{ $coupon->available_end_time}}
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
            </div>

            <table id="data-table-selection" class="table table-striped ">
                <thead>
                <tr>
                    <th>发放记录 : 时间</th>
                    <th>发放数量</th>
                    <th></th>
                </tr>
                </thead>
                <tbody>
                @foreach ($coupon_log as $item)
                    <tr>
                        <td>{{ $item->created_at}}</td>
                        <td>{{ $item->num}}</td>
                        <td>
                            <a href="{{route('coupon.record',['coupon_id'=>$item->coupon_id,'record_date'=>$item->created_at])}}" >
                                <button class="btn btn-info waves-effect">查看详情</button>
                            </a>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>

        </div>
    </div>
@endsection
