@extends('layouts.material')
@section('content')
<div class="container">
    <div class="block-header">
        <h2>用户信息列表</h2>
    </div>
    <div class="card">
        <div class="card-header card-padding">
            <form action="" method='GET'>
                <div class="row">
                    <div class="col-sm-2">
                        <div class="input-group form-group">
                            <span class="input-group-addon"><i class="zmdi zmdi-search"></i></span>
                            <div class="dtp-container fg-line">
                                <input type="text" class="form-control" placeholder="手机号" name='search_mobile'
                                       value="{{ request('search_mobile') }}">
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-2">
                        <div class="input-group form-group">
                            <span class="input-group-addon"><i class="zmdi zmdi-search"></i></span>
                            <div class="dtp-container fg-line">
                                <input type="text" class="form-control" placeholder="昵称/姓名" name='nickname'
                                       value="{{ request('nickname') }}">
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
                                    <select class="selectpicker" name="search_province"
                                            onchange="getcity(this.value)">
                                        <option value="">全部省</option>
                                        @foreach($areaPs as $areaP)
                                        <option value="{{$areaP->area_id}}"
                                                @if($areaP->area_id == request('search_province')) selected @endif >{{$areaP->area_name}}</option>
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
                                    <span id="areaC">
                                        <select class="selectpicker" name="search_city">
                                            <option value="">全部市</option>
                                            @if($areaC_search)
                                            @foreach($areaC_search as $areaC)
                                            <option value="{{$areaC->area_id}}"
                                                    @if($areaC->area_id == request('search_city')) selected @endif >{{$areaC->area_name}}</option>
                                            @endforeach
                                            @endif
                                        </select>
                                    </span>
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
                                    <select class="selectpicker" name="search_vip">
                                        <option value="">是否为和会员</option>
                                        @foreach($user_vip_flg as $key => $vip)
                                        <option value="{{$key}}"
                                                @if(request('search_vip') == $key) selected @endif >{{$vip}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

                <div class="row">
                    <div class="col-sm-2">
                        <div class="input-group form-group">
                            <span class="input-group-addon"><i class="zmdi zmdi-caret-down-circle zmdi-hc-fw"></i></span>
                            <div class="fg-line">
                                <select class="form-control" name="has_mobile" id="has_mobile">
                                    <option value="">注册手机号</option>
                                    <option value="yes" @if(request('has_mobile')=='yes') selected @endif>是</option>
                                    <option value="no" @if(request('has_mobile')=='no') selected @endif>不是</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-2">
                        <div class="input-group form-group">
                            <span class="input-group-addon"><i class="zmdi zmdi-calendar"></i></span>
                            <div class="dtp-container fg-line">
                                <input type="text" class="form-control date-picker" placeholder="注册时间（开始）"
                                       name='search_time_s' value="{{ request('search_time_s') }}">
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-2">
                        <div class="input-group form-group">
                            <span class="input-group-addon"><i class="zmdi zmdi-calendar"></i></span>
                            <div class="dtp-container fg-line">
                                <input type="text" class="form-control date-picker" placeholder="注册时间（结束）"
                                       name='search_time_e' value="{{ request('search_time_e') }}">
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-2">
                        <div class="input-group form-group">
                            <span class="input-group-addon"><i class="zmdi zmdi-trending-up"></i></span>
                            <div class="dtp-container fg-line">
                                <input type="text" class="form-control" placeholder="成长值(大于)" name='search_grow_s'
                                       value="{{ request('search_grow_s') }}">
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-2">
                        <div class="input-group form-group">
                            <span class="input-group-addon"><i class="zmdi zmdi-trending-up"></i></span>
                            <div class="dtp-container fg-line">
                                <input type="text" class="form-control" placeholder="成长值(小于)" name='search_grow_e'
                                       value="{{ request('search_grow_e') }}">
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-2">
                        <div class="input-group form-group">
                            <span class="input-group-addon"><i class="zmdi zmdi-trending-up"></i></span>
                            <div class="dtp-container fg-line">
                                <input type="text" class="form-control" placeholder="和会员天数(大于)" name='search_left_day_s'
                                       value="{{ request('search_left_day_s') }}">
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-2">
                        <div class="input-group form-group">
                            <span class="input-group-addon"><i class="zmdi zmdi-trending-up"></i></span>
                            <div class="dtp-container fg-line">
                                <input type="text" class="form-control" placeholder="和会员天数(小于)" name='search_left_day_e'
                                       value="{{ request('search_left_day_e') }}">
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-2">
                        <div class="input-group form-group">
                            <span class="input-group-addon"><i class="zmdi zmdi-search"></i></span>
                            <div class="dtp-container fg-line">
                                <input type="text" class="form-control" placeholder="爱心大使手机" name='lover_key'
                                       value="{{ request('lover_key') }}">
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-2">
                        <div class="input-group form-group">
                            <span class="input-group-addon"><i
                                    class="zmdi zmdi-caret-down-circle zmdi-hc-fw"></i></span>
                            <div class="dtp-container fg-line">
                                <div class="select">
                                    <select class="selectpicker" name="search_lover">
                                        <option value="" @if(request('search_lover') == '') selected @endif>是否关联爱心大使</option>
                                        <option value="yes" @if(request('search_lover') == 'yes') selected @endif >是</option>
                                        <option value="no" @if(request('search_lover') == 'no') selected @endif >否</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-2">
                        <div class="input-group form-group">
                            <span class="input-group-addon"><i class="zmdi zmdi-calendar"></i></span>
                            <div class="dtp-container fg-line">
                                <input type="text" class="form-control date-picker" placeholder="关联爱心大使时间（开始）"
                                       name='search_lover_time_s' value="{{ request('search_lover_time_s') }}">
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-2">
                        <div class="input-group form-group">
                            <span class="input-group-addon"><i class="zmdi zmdi-calendar"></i></span>
                            <div class="dtp-container fg-line">
                                <input type="text" class="form-control date-picker" placeholder="关联爱心大使时间（结束）"
                                       name='search_lover_time_e' value="{{ request('search_lover_time_e') }}">
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-2 text-right">
                        <button type="submit" class="btn btn-primary btn-xs  waves-effect">搜索</button>
                        <a href="#" target="_blank" class="btn btn-success btn-xs waves-effect">导出</a>
                    </div>
                </div>
            </form>
        </div>


        <div class="card-body table-responsive">
            <table id="data-table-selection" class="table table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>昵称</th>
                        <th>姓名</th>
                        <th>手机号</th>
                        <th>角色</th>
                        <th>称呼</th>
                        <th>城市</th>
                        <th>首次登录时间</th>
                        <th>注册时间</th>
                        <th>成长值</th>
                        <th>是否为和会员</th>
                        <th>和会员天数</th>
                        <th>爱心大使</th>
                        <th>操作</th>
                        <th>禁用/启用</th>
                    </tr>
                </thead>
                <tbody>
                    @if(count($users))
                    @foreach($users as $item)
                    <tr id="b{{$item->id}}">
                        <td>{{$item->id}}</td>
                        <td>{{$item->nickname}}</td>
                        <td>{{$item->realname}}</td>
                        <td>{{$item->mobile}}</td>
                        <td>@if($item->role){{$user_role[$item->role]}}@endif</td>
                        <td>@if($item->label){{$user_label[$item->label]}}@endif</td>
                        <td>@if($item->province){{$areas[$item->province]}}@endif @if($item->city){{$areas[$item->city]}}@endif</td>
                        <td>{{$item->created_at}}</td>
                        <td>{{$item->register_at}}</td>
                        <td>{{$item->grow}}</td>
                        <td>@if($item->vip_flg){{$user_vip_flg[$item->vip_flg]}}@endif</td>
                        <td>{{vip_left_day_text($item->vip_forever,$item->vip_left_day)}}</td>
                        <td>@if($item->lover){{$item->lover->nickname}}/{{$item->lover->mobile}}@endif</td>
                        <td>
                            <a href="{{ route('user.show',['id'=>$item->id]) }}" class="btn bgm-orange waves-effect btn-xs" title="详情">
                                <i class="zmdi zmdi-eye"></i>
                            </a>

                            <a href="{{ route('user.edit',['id'=>$item->id]) }}" class="btn bgm-orange waves-effect btn-xs" title="修改">
                                <i class="zmdi zmdi-edit"></i>
                            </a>

                            <a href="{{ route('user.leftday',['id'=>$item->id]) }}" class="btn bgm-orange waves-effect btn-xs" title="动态">
                                <i class="zmdi zmdi-account"></i>
                            </a>
                        </td>
                        <td>
                            <div class="toggle-switch" data-ts-color="blue">
                                <input id="ts{{$item->id}}" type="checkbox" hidden="hidden"
                                       value="{{$item->id}}" @if($item->block==1) checked @endif>
                                       <label for="ts{{$item->id}}" class="ts-helper"></label>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                    @endif
                </tbody>
            </table>
            {!! $users->render() !!}
        </div>
    </div>
</div>
@endsection

@section('script')
<!-- 获取城市 -->
<script type="text/javascript">
    function getcity(obj) {
    var city = "<select  class='selectpicker' id='search_city' name='search_city'>";
    var arrcity = new Array();
    arrcity = <?php print_r($arrareaCs); ?>;
    arrcity = arrcity[obj];
    console.log(arrcity);
    for (var i in arrcity) {
    city += "<option value='" + arrcity[i]['area_id'] + "'>" + arrcity[i]['area_name'] + "</option>";
    }
    city += "</select>";
    console.log(city);
    document.getElementById("areaC").innerHTML = city;
    $('#search_city').selectpicker();
    }
</script>

<!-- 禁用/启用 -->
<script type="text/javascript">
    $(document).ready(function () {
    $('body').on('change', '#data-table-selection input:checkbox', function () {
    if ($(this).is(':checked')) {
    $.ajax({
    type: 'post',
            url: '{{route('user.unlock')}}',
            data: {id: $(this).val()},
            dataType: 'json',
            success: function (res) {
            if (res.status == 0) {
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
    }
    else {
    $.ajax({
    type: 'post',
            url: '{{route('user.block')}}',
            data: {id: $(this).val()},
            dataType: 'json',
            success: function (res) {
            if (res.status == 0) {
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
    }
    });
    $('form:first .btn-success:last').click(function() {
    var url = $(this).closest('form').serialize();
    $(this).attr('href', '/user?' + url + '&export=1');
    });
    });
</script>
@endsection
