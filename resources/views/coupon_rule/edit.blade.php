@extends('layouts.material')
@section('content')
    <div class="container">
        <div class="block-header">
            <h2><a href="{{route('coupon_rule')}}">获取规则</a>  -> 编辑</h2>
        </div>
        <div class="card">
            <div class="card-body card-padding">
                @if (count($errors) > 0)
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form id="post_form" action="{{route('coupon_rule.update',['id'=>$coupon_rule->id])}}" method="post">
                    {!! csrf_field() !!}
                    <div class="row">
                        <div class="col-sm-4">
                            <div class="form-group fg-line ">
                                <label>名称</label>
                                <input type="text" value="{{$coupon_rule->name}}"  name="name"    class="form-control input-sm" >
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-2">
                            <div class="form-group fg-line ">
                                <label>获取规则</label>
                                <select id="select_type" class="selectpicker" name="rule_id">
                                    <option value=""  >请选择</option>
                                    @foreach($coupon_get_rule as $k=>$v)
                                        <option value="{{ $k }}" @if($k==$coupon_rule->rule_id) selected @endif>{{$v}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-2">
                            <div class="form-group fg-line ">
                                <label>优惠券模板</label>
                                <select class="selectpicker" name="coupon_id[]" multiple="multiple">
                                    <option value=""  >请选择</option>
                                    @foreach($coupon as $k=>$v)
                                        <option value="{{ $k }}" @if(in_array($k, $coupon_rule->coupon_id)) selected @endif>{{$v}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row type_a">
                        <div class="col-sm-2">
                            <div class="form-group fg-line ">
                                <label>邀请人可领取红包的所属</label>
                                <select  class="selectpicker" name="agency_id">
                                    <option value=""  >请选择</option>
                                    @foreach($agency as $k=>$v)
                                        <option value="{{ $k }}" @if($k==$coupon_rule->agency_id) selected @endif>{{$v}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row type_a">
                        <div class="col-sm-2">
                            <div class="form-group fg-line ">
                                <label>领取的红包金额<small>(单位：元)</small></label>
                                <input type="text" value="{{$coupon_rule->bouns}}"  name="bouns"   class="form-control input-sm" >
                            </div>
                        </div>
                    </div>

                    <div class="form-group fg-line">
                        <button class="btn bgm-cyan waves-effect" >保存</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script type="text/javascript">
        $(function(){
            $("#select_type").change(function(){
                select_type($(this).val());
            });

            select_type($("#select_type").val());
            function select_type(select_scope_id){
                $('.type_a').hide();
                if(select_scope_id == 1)
                {
                    $('.type_a').show();
                }
            }
        })
    </script>
@endsection