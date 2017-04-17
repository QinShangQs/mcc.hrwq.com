@extends('layouts.material')
@section('content')
    <div class="container">
        <div class="block-header">
            <h2>平台收益 <span class="c-red">(财务流水账)</span></h2>
        </div>
        <div class="card">
            <div class="btn btn-info waves-effect "><span class="f-500 c-red">亿美软通短信余额</span>  ： {{$smsBalance or 0}}(元)</div>
            <div class="card-header card-padding">
                <form action="{{ route('income.platform') }}" method='GET'>
                    <div class="row">

                        <div class="col-sm-2">
                            <div class="input-group form-group">
                                <span class="input-group-addon"><i class="zmdi zmdi-caret-down-circle zmdi-hc-fw"></i></span>
                                <div class="dtp-container fg-line">
                                    <div class="select">
                                        <select class="selectpicker" name="income_log_type">
                                            <option value="">类型</option>
                                            @foreach($income_log_type as $key => $type)
                                                <option value="{{$key}}" @if(request('income_log_type') == $key) selected @endif >{{$type}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>


                        <div class="col-sm-2">
                            <div class="input-group form-group">
                                <span class="input-group-addon"><i class="zmdi zmdi-caret-down-circle zmdi-hc-fw"></i></span>
                                <div class="dtp-container fg-line">
                                    <div class="select">
                                        <select class="selectpicker" name="income_type">
                                            <option value="">来源</option>
                                            @foreach($income_in_type as $key => $type)
                                                <option value="{{$key}}" @if(request('income_type') == $key) selected @endif >{{$type}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-2">
                            <div class="input-group form-group">
                                <span class="input-group-addon"><i class="zmdi zmdi-caret-down-circle zmdi-hc-fw"></i></span>
                                <div class="dtp-container fg-line">
                                    <div class="select">
                                        <select class="selectpicker" name="income_pay_type">
                                            <option value="">支付方式</option>
                                            @foreach($income_pay_type as $key => $type)
                                                <option value="{{$key}}" @if(request('income_pay_type') == $key) selected @endif >{{$type}}</option>
                                            @endforeach
                                        </select>
                                    </div>
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
                                        <select class="selectpicker" name="search_role">
                                            <option value="">角色</option>
                                            @foreach($user_role as $key => $role)
                                                    <option value="{{$key}}" @if(request('search_role') == $key) selected @endif >{{$role}}</option>
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
                                <span class="input-group-addon"><i class="zmdi zmdi-calendar"></i></span>
                                <div class="dtp-container fg-line">
                                    <input type="text" class="date-picker form-control" name='s_time' value="{{ request('s_time') }}" placeholder="时间段-开始时间">
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-2">
                            <div class="input-group">
                                <span class="input-group-addon"><i class="zmdi zmdi-calendar"></i></span>
                                <div class="dtp-container fg-line">
                                    <input type="text" class="date-picker form-control" name='e_time' value="{{ request('e_time') }}" placeholder="时间段-结束时间">
                                </div>
                            </div>
                        </div>

                         <div class="col-sm-4">
                                <button type="submit" class="btn btn-primary btn-sm  waves-effect">搜索</button>
                         </div>

                     </div>
                </form>
            </div>

            <div class="card-body table-responsive">
                <table id="data-table-selection" class="table table-striped">
                    <button class="btn bgm-red waves-effect log_btn each-item m-l-25  m-b-5"  data-target="#logModel" data-toggle="modal"><i class="zmdi zmdi-trending-up"></i> 平台记账 </button>
                    <thead>
                    <tr>
                        <th>时间</th>
                        <th>类型</th>
                        <th>金额 <small class="c-red">(元)</small></th>
                        <th>平台余额 <small class="c-red">(元)</small></th>
                        <th>支付方式</th>
                        <th>来源</th>
                        <th>用户</th>
                        <th>角色</th>
                        <th>所属订单</th>
                        <th>说明</th>
                        <th>操作</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach ($data as $item)
                        <tr>
                            <td>{{ $item->created_at }}</td>
                            @if($item->log_type == 1)
                                 <td class="f-500 c-red"> {{ $income_log_type[$item->log_type]}}</td>
                            @else
                                <td class="f-500 c-green">{{ $income_log_type[$item->log_type]}}</td>
                            @endif

                            @if($item->log_type == 1)
                                <td class="f-500 c-red"> +{{ $item->amount}}</td>
                            @else
                                <td class="f-500 c-green"> -{{$item->amount}}</td>
                            @endif
                            <td class="f-500 c-blue">{{ $item->total_amount}}</td>
                            <td>{{ $income_pay_type[$item->pay_mod]}}</td>
                            <td>{{  $item->income_type == 0 ? '无' :$income_in_type[$item->income_type]}}</td>
                            <td>
                                @if(isset($item->user))
                                      <a href="{{route('user.show',['id'=>$item->user->id])}}">{{ $item->user->nickname}}</a>
                                @endif
                            </td>
                            <td>
                              @if(isset($item->user))
                                    @if($item->user->role==1)
                                        <button class="btn bgm-gray waves-effect">{{ $user_role[$item->user->role]}}</button>
                                    @elseif($item->user->role==2)
                                        <button class="btn bgm-lightgreen waves-effect">{{ $user_role[$item->user->role]}}</button>
                                    @else
                                        <button class="btn bgm-amber waves-effect">{{ $user_role[$item->user->role]}}</button>
                                    @endif
                               @endif
                            </td>
                            <td>
                                <a>{{ $item->order_no}}</a>
                            </td>
                            <td>{{ $item->remark}}</td>
                            <td>
                                <a title="查看详情" href="{{route('income.platform_show',['id'=>$item->id])}}">
                                    <button class="btn bgm-orange waves-effect"><i class="zmdi zmdi-eye"></i></button>
                                </a>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
                {!! $data->render() !!}
            </div>
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
                                平台收益
                            </h4>
                        </div>
                        <div class="modal-body">
                            <div class="select">
                                <select class="selectpicker" name="log_type" id="log_type" >
                                    <option value="" > 选择记录类型  </option>
                                    @foreach (config('constants.income_log_type') as $k=>$v)
                                        <option value="{{$k}}" >{{$v}}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="select">
                                <select class="selectpicker" name="pay_mod" id="pay_mod" >
                                    <option value="" > 选择支付方式  </option>
                                    @foreach (config('constants.income_pay_type') as $k=>$v)
                                        <option value="{{$k}}" >{{$v}}</option>
                                    @endforeach
                                </select>
                            </div>

                            <input type="text" class="form-control" placeholder="金额" name='amount' value="" id="amount" >

                            <div class="select">
                                <select class="selectpicker" name="income_type" id="income_type" >
                                    <option value="" > 选择来源 </option>
                                    @foreach (config('constants.income_in_type') as $k=>$v)
                                        <option value="{{$k}}" >{{$v}}</option>
                                    @endforeach
                                </select>
                            </div>

                            备注<textarea type="text" class="form-control" name='remark' id="remark"></textarea>
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
    </div>
@endsection


@section('script')
    <script type="text/javascript">

        $('#log_submit').click(function(){
            var amount = $('#amount').val();
            var log_type = $('#log_type').val();
            var pay_mod = $('#pay_mod').val();
            var income_type = $('#income_type').val();
            var remark = $('#remark').val();

            $.ajax({
                type: 'post',
                url: '{{route('income.platform_log')}}',
                data: {amount:amount,log_type:log_type,pay_mod:pay_mod,remark:remark,income_type:income_type},
                dataType: 'json',
                success: function (res) {
                    if(res.code == 0){
                        $('#logModel').modal('hide')
                        swal(res.message);
                        location.href='{{route('income.platform')}}';
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