@extends('layouts.material')
@section('content')
    <div class="container">
        <div class="block-header">
            <h2>提现申请 - 指导师/普通用户</h2>
        </div>
        <div class="card">
            <div class="card-header card-padding">
                <form action="{{ route('income.cash') }}" method='GET'>
                    <div class="row">
                        <div class="col-sm-2">
                            <div class="input-group">
                                <span class="input-group-addon"><i
                                            class="zmdi zmdi-caret-down-circle zmdi-hc-fw"></i></span>
                                <div class="fg-line">
                                    <select class="form-control" name="province" id="select_province">
                                        <option value="">选择省</option>
                                        @foreach (get_province() as $k=>$v)
                                            <option value="{{$k}}"
                                                    @if(request('province')==$k) selected @endif>{{$v}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-2">
                            <div class="input-group">
                                <span class="input-group-addon"><i
                                            class="zmdi zmdi-caret-down-circle zmdi-hc-fw"></i></span>
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
                                <span class="input-group-addon"><i
                                            class="zmdi zmdi-caret-down-circle zmdi-hc-fw"></i></span>
                                <div class="dtp-container fg-line">
                                    <div class="select">
                                        <select class="selectpicker" name="search_role">
                                            <option value="">角色</option>
                                            @foreach($user_role as $key => $role)
                                                <option value="{{$key}}"
                                                        @if(request('search_role') == $key) selected @endif >{{$role}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-2">
                            <div class="input-group form-group">
                                <span class="input-group-addon"><i
                                            class="zmdi zmdi-caret-down-circle zmdi-hc-fw"></i></span>
                                <div class="dtp-container fg-line">
                                    <div class="select">
                                        <select class="selectpicker" name="apply_status">
                                            <option value="">状态</option>
                                            @foreach($income_cash_state as $key => $v)
                                                @if($key!=2)
                                                    <option value="{{$key}}"
                                                            @if(request('apply_status') == $key) selected @endif >{{$v}}</option>
                                                @endif
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
                                    <select class="selectpicker" name="pick_mod">
                                        <option value=""> 提现金额</option>
                                        <option value="1" @if(request('pick_mod')==1) selected @endif> 大于</option>
                                        <option value="2" @if(request('pick_mod')==2) selected @endif> 等于</option>
                                        <option value="3" @if(request('pick_mod')==3) selected @endif> 小于</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-2">
                            <div class="input-group">
                                <span class="input-group-addon"></span>
                                <div class="fg-line">
                                    <input type="text" class="form-control" name='cash_amount'
                                           value="{{ request('cash_amount') }}" placeholder="提现金额">
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-2">
                            <div class="input-group">
                                <span class="input-group-addon"><i class="zmdi zmdi-calendar"></i></span>
                                <div class="dtp-container fg-line">
                                    <input type="text" class="date-picker form-control" name='s_time'
                                           value="{{ request('s_time') }}" placeholder="申请-开始时间">
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-2">
                            <div class="input-group">
                                <span class="input-group-addon"><i class="zmdi zmdi-calendar"></i></span>
                                <div class="dtp-container fg-line">
                                    <input type="text" class="date-picker form-control" name='e_time'
                                           value="{{ request('e_time') }}" placeholder="申请-结束时间">
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
                <table id="data-table-selection" class="table table-striped bootgrid-table">
                    <button class="btn btn-success  waves-effect sa-warning each-item m-l-15 m-b-5"
                            onclick="send_to_selected();"><i class="zmdi zmdi-trending-up"></i> 转账给选中的项
                    </button>
                    <thead>
                    <tr>
                        <th class="select-cell">
                            <div class="checkbox">
                                <label>
                                    <input class="checkbox" type="checkbox" value="" onclick="checkAll(this);">
                                    <i class="input-helper"></i></label>
                            </div>
                        </th>
                        <th>ID</th>
                        <th>角色</th>
                        <th>手机号</th>
                        <th>昵称</th>
                        <th>地区</th>
                        <th>余额
                            <small class="c-red">(元)</small>
                        </th>
                        <th>提现金额
                            <small class="c-red">(元)</small>
                        </th>
                        <th>申请时间</th>
                        <th>转账时间</th>
                        <th>状态</th>
                        <th>操作</th>
                    </tr>
                    </thead>

                    <tbody>
                    @foreach ($data as $item)
                        <tr>
                            <td class="select-cell">
                                @if($item->apply_status == 1)
                                    <div class="checkbox">
                                        <label>
                                            <input type="checkbox" name='item_ids' class="item_id"
                                                   value="{{ $item->id }}">
                                            <i class="input-helper"></i></label>
                                    </div>
                                @endif
                            </td>
                            <td>{{ $item->id }}</td>
                            <td>
                                @if($item->user->role==1)
                                    <button class="btn bgm-gray waves-effect">{{ $user_role[$item->user->role]}}</button>
                                @elseif($item->user->role==2)
                                    <button class="btn bgm-lightgreen waves-effect">{{ $user_role[$item->user->role]}}</button>
                                @else
                                    <button class="btn bgm-amber waves-effect">{{ $user_role[$item->user->role]}}</button>
                                @endif
                            </td>

                            <td>{{ $item->user->mobile}}</td>
                            <td><a href="{{route('user.show',['id'=>$item->user->id])}}">{{ $item->user->nickname}}</a>
                            </td>
                            <td>
                                {{ @$item->user->c_province->area_name}} - {{@$item->user->c_city->area_name}}
                            </td>

                            <td><strong class="c-green"> {{$item->user->current_balance}}</strong></td>
                            <td><strong class="c-red"> {{$item->cash_amount}}</strong></td>

                            <td>{{$item->created_at}}</td>
                            <td>@if($item->apply_status==3){{$item->updated_at}}@endif</td>
                            <td class="@if($item->apply_status == 1) c-red @elseif($item->apply_status == 2) c-gray @else c-green  @endif">{{$income_cash_state[$item->apply_status]}}</td>
                            <td>
                                <a title="查看记录" href="{{route('income.cash_show',['id'=>$item->id])}}">
                                    <button class="btn bgm-orange waves-effect"><i class="zmdi zmdi-eye"></i></button>
                                </a>
                                @if($item->apply_status == 1)
                                    <button id="approve_btn_{{$item->id}}" class="btn bgm-blue waves-effect approve_btn" data-id="{{$item->id}}">通过
                                    </button>
                                    <button id="refuse_btn_{{$item->id}}" class="btn bgm-red waves-effect refuse_btn"
                                            data-target="#myModal_{{$item->id}}" data-toggle="modal">驳回
                                    </button>
                                    <div class="modal" id="myModal_{{$item->id}}" tabindex="-1" role="dialog"
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
                                                                <textarea type="text" class="form-control refuse_reason"
                                                                          name="refuse_reason"> </textarea>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-default"
                                                            data-dismiss="modal">取消
                                                    </button>
                                                    <button type="button" class="btn btn-primary refuse_submit" data-id="{{$item->id}}">
                                                        确认
                                                    </button>
                                                </div>
                                            </div><!-- /.modal-content -->
                                        </div><!-- /.modal -->
                                    </div>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
                {!! $data->render() !!}
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script type="text/javascript">
        //全选/反选
        function checkAll(obj) {
            $("#data-table-selection input[type='checkbox']").prop('checked', $(obj).prop('checked'));
        }
        function send_to_selected() {
            var str = $(".item_id");
            var item_num = str.length;
            var ids = '';

            for (var i = 0; i < item_num; i++) {
                if (str[i].checked == true) {
                    ids += str[i].value + ",";
                }
            }

            if (ids == "") {
                swal("请先选择要转账的项");
            } else {
                swal({
                    title: "确认要转账给选中的项吗?",
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#DD6B55",
                    confirmButtonText: "确认转账",
                    cancelButtonText: "取消",
                    closeOnConfirm: false
                }, function () {
                    $.ajax({
                        type: 'post',
                        url: '{{route('income.withdraw.bulk.approve')}}',
                        data: {id: ids},
                        dataType: 'json',
                        success: function (res) {
                            if (res.code == 0) {
                                swal({
                                    title: res.message
                                });
                                location.href = '{{route('income.cash')}}';
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
            }
        }

        //默认加载
        var selected_city_id = '{!! request("city") !!}';
        select_province(selected_city_id);

        //适用范围改变触发
        $('#select_province').change(function () {
            select_province(selected_city_id);
        });
        function select_province(selected_city_id) {
            var province_id = $('#select_province').val();
            $.ajax({
                type: 'post',
                url: '{{route('coupon.select_city')}}',
                data: {province_id: province_id, selected_city_id: selected_city_id},
                success: function (res) {
                    $('#select_city').html(res);
                }
            });
        }

        $(document).ready(function () {
            //单条通过
            $('.approve_btn').click(function () {
                var income_cash_id = $(this).data('id');
                swal({
                    title: "确认要转账给选中的项吗?",
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#DD6B55",
                    confirmButtonText: "确认转账",
                    cancelButtonText: "取消",
                    closeOnConfirm: false
                }, function () {
                    $.ajax({
                        type: 'post',
                        url: '{{route('income.withdraw.approve')}}',
                        data: {id: income_cash_id},
                        dataType: 'json',
                        success: function (res) {
                            if (res.code == 0) {
                                swal({
                                    title: res.message
                                });
                                location.href = '{{route('income.cash')}}';
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
            });
            //单条拒绝
            $('.refuse_submit').click(function () {
                var income_cash_id = $(this).data('id');
                var refuse_reason = $(this).closest('.modal').find('textarea').val();
                console.log(refuse_reason);
                $.ajax({
                    type: 'post',
                    url: '{{route('income.withdraw.reject')}}',
                    data: {"id": income_cash_id, "refuse_reason": refuse_reason},
                    dataType: 'json',
                    success: function (res) {
                        if (res.code == 0) {
                            swal({
                                title: res.message
                            });
                            location.href = '{{route('income.cash')}}';
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
            });
        });
    </script>
@endsection