@extends('layouts.material')
@section('content')
    <div class="container">
        <div class="block-header">
            <h2>积分管理</h2>
        </div>
        <div class="card">
            <div class="card-header card-padding">
                <form action="{{ route('income.point') }}" method='GET'>
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
                                <span class="input-group-addon"><i class="zmdi  zmdi-caret-down-circle"></i></span>
                                <div class="select">
                                    <select class="selectpicker" name="pick_mod" >
                                        <option value="" > 积分值 </option>
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
                                    <input type="text" class="form-control"  name='score' value="{{ request('score') }}" placeholder="积分值">
                                </div>
                            </div>
                        </div>

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

                        <div class="col-sm-2">
                            <button type="submit" class="btn btn-primary btn-sm  waves-effect">搜索</button>
                        </div>

                         <div class="col-sm-2">
                             <button id="empty_score" class="btn bgm-red waves-effect"><i class="zmdi zmdi-refresh-sync-alert zmdi-hc-fw"></i>积分清零</button>
                         </div>
                     </div>
                </form>
            </div>

            <div class="card-body table-responsive">
                <table id="data-table-selection" class="table table-striped">
                    <thead>
                    <tr>
                        <th>ID</th>
                        <th>角色</th>
                        <th>手机号</th>
                        <th>昵称</th>
                        <th>地区</th>
                        <th>积分</th>
                        <th>注册时间</th>
                        <th>操作</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach ($data as $item)
                        <tr>
                            <td>{{ $item->id }}</td>
                            <td>
                                @if($item->role==1)
                                    <button class="btn bgm-gray waves-effect">{{ $user_role[$item->role]}}</button>
                                @elseif($item->role==2)
                                    <button class="btn bgm-lightgreen waves-effect">{{ $user_role[$item->role]}}</button>
                                @else
                                    <button class="btn bgm-amber waves-effect">{{ $user_role[$item->role]}}</button>
                                @endif
                            </td>
                            <td>{{ $item->mobile}}</td>
                            <td><a href="{{route('user.show',['id'=>$item->id])}}">{{ $item->nickname}}</a></td>
                            <td> {{ @$item->c_province->area_name}} - {{@$item->c_city->area_name}}</td>
                            <td><strong class="c-red"> {{$item->score}}</strong></td>
                            <td><strong class="c-gray"> {{$item->created_at}}</strong></td>
                            <td>
                                <a title="查看记录" href="{{route('income.point_show',['id'=>$item->id])}}">
                                    <button class="btn bgm-orange waves-effect"><i class="zmdi zmdi-view-list-alt"></i></button>
                                </a>

                                    <button class="btn bgm-red waves-effect log_btn"  data-target="#logModel" data-toggle="modal"  val_id="{{$item->id}}"><i class="zmdi zmdi-plus"></i></button>
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
                                增加用户积分
                            </h4>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="form-group fg-line">
                                        <label>积分</label>
                                        <input type="text" class="form-control" placeholder="积分值" name='amount' value="" id="amount" >
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="form-group fg-line">
                                        <label>增加理由</label>
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
    </div>
@endsection


@section('script')
    <script type="text/javascript">
        var is_click = 0;
        $('#empty_score').click(function(e){
            e.preventDefault();
            if(is_click == 1)
            {
                return false;
            }

            is_click = 1;
            swal({
                title: "请再三确认是否要清空所有用户的积分?",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "确认清空",
                cancelButtonText: "取消",
                closeOnConfirm: false
            }, function (isConfirm) {
                if(isConfirm) {
                    $.ajax({
                        type: 'get',
                        url: '{{route('income.point_empty')}}',
                        dataType: 'json',
                        success: function (res) {
                            is_click = 0;
                            swal(res.message);
                            location.href='{{route('income.point')}}';
                        },
                        error: function (res) {
                            is_click = 0;
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
                } else {
                    is_click = 0;
                }
            })

        })

        $('.log_btn').click(function(){
            $('#log_id').val($(this).attr('val_id'));
        })

        $('#log_submit').click(function(){
            var amount = $('#amount').val();
            var remark = $('#remark').val();
            var s_id = $('#log_id').val();
            $.ajax({
                type: 'post',
                url: '{{route('income.point_update')}}',
                data: {id:s_id,point_value:amount,remark:remark},
                dataType: 'json',
                success: function (res) {
                    if(res.code == 0){
                        $('#logModel').modal('hide')
                        swal(res.message);
                        location.href='{{route('income.point')}}';
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

    </script>
@endsection