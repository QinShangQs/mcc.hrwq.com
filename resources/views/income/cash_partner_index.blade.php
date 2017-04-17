@extends('layouts.material')
@section('content')
    <div class="container">
        <div class="block-header">
            <h2>提现申请 - 合伙人 (<span class="c-red">手动转账，手动记账</span>)</h2>
        </div>
        <div class="card">
            <div class="card-header card-padding">
                <form action="{{ route('income.cash_partner') }}" method='GET'>
                    <div class="row">
                        <div class="col-sm-2">
                            <div class="input-group">
                                <span class="input-group-addon"><i class="zmdi zmdi-caret-down-circle zmdi-hc-fw"></i></span>
                                <div class="fg-line">
                                    <select class="form-control" name="province" id="select_province">
                                        <option value="">选择省</option>
                                        @foreach (get_province() as $k=>$v)
                                            <option value="{{$k}}" @if(request('province')==$k) selected @endif>{{$v}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-2">
                            <div class="input-group">
                                <span class="input-group-addon"><i class="zmdi zmdi-caret-down-circle zmdi-hc-fw"></i></span>
                                <div class="fg-line">
                                    <select class="form-control" name="city" id="select_city">
                                        <option value="">选择市</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-2">
                            <div class="input-group">
                                <span class="input-group-addon"><i class="zmdi zmdi-phone zmdi-hc-fw"></i></span>
                                <div class="fg-line">
                                    <input type="text" class="form-control" placeholder="手机号" name='search_phone'
                                           value="{{ request('search_phone') }}">
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-2">
                            <div class="input-group">
                                <span class="input-group-addon"><i class="zmdi zmdi-account zmdi-hc-fw"></i></span>
                                <div class="fg-line">
                                    <input type="text" class="form-control" placeholder="昵称" name='search_name'
                                           value="{{ request('search_name') }}">
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-2">
                            <div class="input-group form-group">
                                <span class="input-group-addon"><i class="zmdi zmdi-caret-down-circle zmdi-hc-fw"></i></span>
                                <div class="dtp-container fg-line">
                                    <div class="select">
                                        <select class="selectpicker" name="apply_status">
                                            <option value="">状态</option>
                                            @foreach($income_cash_state as $key => $v)
                                                    <option value="{{$key}}" @if(request('apply_status') == $key) selected @endif >{{$v}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                     <div class="row">
                        <div class="col-sm-2">
                            <div class="input-group">
                                <span class="input-group-addon"><i class="zmdi  zmdi-caret-down-circle"></i></span>
                                <div class="select">
                                    <select class="selectpicker" name="pick_mod" >
                                        <option value="" > 提现金额 </option>
                                        <option value="1" @if(request('pick_mod')==1) selected @endif> 大于 </option>
                                        <option value="2" @if(request('pick_mod')==2) selected @endif> 等于 </option>
                                        <option value="3" @if(request('pick_mod')==3) selected @endif> 小于 </option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-2">
                            <div class="input-group">
                                <span class="input-group-addon"></span>
                                <div class="fg-line">
                                    <input type="text" class="form-control"  name='cash_amount' value="{{ request('cash_amount') }}" placeholder="提现金额">
                                </div>
                            </div>
                        </div>

                         <div class="col-sm-2">
                             <div class="input-group">
                                 <span class="input-group-addon"><i class="zmdi zmdi-calendar"></i></span>
                                 <div class="dtp-container fg-line">
                                     <input type="text" class="date-picker form-control" name='s_time' value="{{ request('s_time') }}" placeholder="申请-开始时间">
                                 </div>
                             </div>
                         </div>
                         <div class="col-sm-2">
                             <div class="input-group">
                                 <span class="input-group-addon"><i class="zmdi zmdi-calendar"></i></span>
                                 <div class="dtp-container fg-line">
                                     <input type="text" class="date-picker form-control" name='e_time' value="{{ request('e_time') }}" placeholder="申请-结束时间">
                                 </div>
                             </div>
                         </div>
                        <div class="col-sm-2">
                            <button type="submit" class="btn btn-primary  waves-effect">搜索</button>
                        </div>
                     </div>
                </form>
            </div>

            <div class="card-body table-responsive">
                <table id="data-table-selection" class="table table-striped">
                    <thead>
                    <tr>
                        <th>ID</th>
                        <th>手机号</th>
                        <th>昵称</th>
                        <th>余额 <small class="c-red">(元)</small></th>
                        <th>提现金额 <small class="c-red">(元)</small></th>
                        <th>申请时间</th>
                        <th>处理时间</th>
                        <th>状态</th>
                        <th>操作</th>
                    </tr>
                    </thead>

                    <tbody>
                    @foreach ($data as $item)
                        <tr>
                            <td>{{ $item->id }}</td>
                            <td>{{ $item->user->mobile}}</td>
                            <td><a href="{{route('user.show',['id'=>$item->user->id])}}">{{ $item->user->nickname}}</a></td>
                            <td><strong class="c-green"> {{$item->user->current_balance}}</strong></td>
                            <td><strong class="c-red"> {{$item->cash_amount}}</strong></td>

                            <td>{{$item->created_at}}</td>
                            <td>{{$item->updated_at}}</td>
                            <td id="cash_item_{{$item->id}}"  class="@if($item->apply_status == 1) c-red @elseif($item->apply_status == 2) c-gray @else c-green  @endif">{{$income_cash_state[$item->apply_status]}}</td>
                            <td>
                                <a title="查看记录" href="{{route('income.cash_partner_show',['id'=>$item->id])}}">
                                    <button class="btn bgm-orange waves-effect"><i class="zmdi zmdi-eye"></i></button>
                                </a>
                                @if($item->apply_status == 1)
                                     <button id="refuse_btn_{{$item->id}}" class="btn bgm-red waves-effect refuse_btn"  data-target="#myModal" data-toggle="modal" val_id="{{$item->id}}">驳回</button>
                                    <button  id="log_btn_{{$item->id}}" class="btn bgm-blue waves-effect log_btn"  data-target="#logModel" data-toggle="modal" val_id="{{$item->id}}" amount="{{$item->cash_amount}}">已转记账</button>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
                {!! $data->render() !!}
            </div>

            <!-- 模态框（Modal） -->
            <div class="modal" id="myModal" tabindex="-1" role="dialog"
                 aria-labelledby="myModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-sm">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close"
                                    data-dismiss="modal" aria-hidden="true">
                                &times;
                            </button>
                            <h4 class="modal-title" id="myModalLabel">
                                 驳回
                            </h4>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="form-group fg-line">
                                        <label>驳回原因</label>
                                        <textarea type="text" class="form-control" name='refuse_reason' id="refuse_reason"> </textarea>
                                    </div>
                                </div>
                            </div>
                            <input type="hidden" value="" id="refuse_cash_id">
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default"
                                    data-dismiss="modal">取消
                            </button>
                            <button type="button" class="btn btn-primary" id="refuse_submit">
                                确认
                            </button>
                        </div>
                    </div><!-- /.modal-content -->
                </div><!-- /.modal -->
        </div>

            <!-- 模态框（Modal） -->
            <div class="modal" id="logModel" tabindex="-1" role="dialog"
                 aria-labelledby="myModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-sm">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close"
                                    data-dismiss="modal" aria-hidden="true">
                                &times;
                            </button>
                            <h4 class="modal-title" id="myModalLabel">
                                  记账     <small>类型:平台支出  （合伙人提现）</small>
                            </h4>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="form-group fg-line">
                                        <label>金额<small>（单位：元）</small></label>
                                        <input type="text" class="form-control" placeholder="金额" name='amount' value="" id="amount" disabled>
                                     </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="form-group fg-line">
                                        <label>支付方式</label>
                                        <div class="select">
                                            <select class="selectpicker" name="pay_mod" id="pay_mod" >
                                                <option value="1" > 微信支付 </option>
                                                <option value="2" > 银行转账 </option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="form-group fg-line">
                                        <label>备注</label>
                                        <textarea type="text" class="form-control" name='remark' id="remark"></textarea>
                                    </div>
                                </div>
                            </div>

                            <input type="hidden" value="" id="log_id">
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default"
                                    data-dismiss="modal">取消
                            </button>
                            <button type="button" class="btn btn-primary" id="log_submit">
                                确认
                            </button>
                        </div>
                    </div><!-- /.modal-content -->
                </div><!-- /.modal -->
            </div>

    </div>
@endsection


@section('script')
    <script type="text/javascript">
        //默认加载
        var selected_city_id = '{!! request("city") !!}';
        select_province(selected_city_id);

        //适用范围改变触发
        $('#select_province').change(function(){
            select_province(selected_city_id);
        })

        function select_province(selected_city_id){
            var province_id = $('#select_province').val();
            $.ajax({
                type: 'post',
                url: '{{route('coupon.select_city')}}',
                data: {province_id: province_id,selected_city_id: selected_city_id},
                success: function (res) {
                    $('#select_city').html(res);
                }
            });
        }

        $('.log_btn').click(function(){
            $('#log_id').val($(this).attr('val_id'));
            $('#amount').val($(this).attr('amount'));
        })

        $('#log_submit').click(function(){
            var amount = $('#amount').val();
            var pay_mod = $('#pay_mod').val();
            var remark = $('#remark').val();
            var s_id = $('#log_id').val();
            $.ajax({
                type: 'post',
                url: '{{route('income.cash_partner_log')}}',
                data: {id:s_id,amount:amount,pay_mod:pay_mod,remark:remark},
                dataType: 'json',
                success: function (res) {
                    if(res.code == 0){
                        $('#refuse_btn_'+s_id).remove();
                        $('#log_btn_'+s_id).remove();
                        $('#cash_item_'+s_id).removeClass('c-red').addClass('c-green').html('已完成');

                        $('#logModel').modal('hide')
                        swal(res.message);
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

        })


        $('.refuse_btn').click(function(){
            $('#refuse_cash_id').val($(this).attr('val_id'));
        })

        $('#refuse_submit').click(function(){
            var reason_txt = $('#refuse_reason').val();
            var s_id = $('#refuse_cash_id').val();
            $.ajax({
                type: 'post',
                url: '{{route('income.cash_refuse')}}',
                data: {id:s_id,refuse_reason:reason_txt},
                dataType: 'json',
                success: function (res) {
                    if(res.code == 0){
                        $('#refuse_btn_'+s_id).remove();
                        $('#log_btn_'+s_id).remove();
                        $('#cash_item_'+s_id).removeClass('c-red').addClass('c-gray').html('驳回');
                        $('#myModal').modal('hide')
                        swal(res.message);
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

        })
    </script>
@endsection
