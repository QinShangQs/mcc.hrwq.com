@extends('layouts.material')
@section('content')
    <div class="container">
        <div class="block-header">
            <h2>和会员产品管理 > 直播激活码维护</h2>
        </div>
        <div class="card">

            <div class="card-header card-padding">
                <form action="" method='GET'>

                    <div class="row">
                        <div class="col-sm-4">
                            <!-- <button type="submit" class="btn btn-primary btn-sm  waves-effect">搜索</button> -->
                            <a href="{{ route('vip.tv_create') }}" class="btn btn-success btn-sm  waves-effect ">
                                <i class="zmdi zmdi-plus zmdi-hc-fw"></i>添加直播激活码

                            </a>
                            <a href="{{ route('vip.tv_import') }}" class="btn btn-primary btn-sm  waves-effect ">
                                导入直播激活码
                            </a>
                        </div>
                    </div>
                    <br/>
                    <div class="row card-padding">
                        <div class="col-sm-2">
                            <div class="input-group">
                                <span class="input-group-addon"><i class="zmdi zmdi-account zmdi-hc-fw"></i></span>
                                <div class="fg-line">
                                    <input type="text" class="form-control" placeholder="直播激活码" name='code' value="{{ request('code') }}">
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-2">
                            <div class="input-group form-group">
                                <span class="input-group-addon"><i class="zmdi zmdi-caret-down-circle zmdi-hc-fw"></i></span>
                                <div class="dtp-container fg-line">
                                    <div class="select">
                                        <select class="selectpicker" name="is_activated">
                                            <option value="">是否被领取</option>
                                                <option value="1" @if(request('is_activated')==1) selected @endif>否</option>
                                                <option value="2" @if(request('is_activated')==2) selected @endif>是</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-2">
                            <div class="input-group">
                                <span class="input-group-addon"><i class="zmdi zmdi-calendar"></i></span>
                                <div class="dtp-container fg-line">
                                    <input type="text" class="date-picker form-control" name='s_time' value="{{ request('s_time') }}" placeholder="领取时间段-开始">
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-2">
                            <div class="input-group">
                                <span class="input-group-addon"><i class="zmdi zmdi-calendar"></i></span>
                                <div class="dtp-container fg-line">
                                    <input type="text" class="date-picker form-control" name='e_time' value="{{ request('e_time') }}" placeholder="领取时间段-截止">
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-2">
                            <div class="input-group">
                                <span class="input-group-addon"><i class="zmdi zmdi-search"></i></span>
                                <div class="dtp-container fg-line">
                                    <input type="text" class="form-control" placeholder="昵称" name='nickname'
                                           value="{{ request('nickname') }}">
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-2">
                            <div class="text-right form-group">
                                <button type="submit" class="btn btn-primary btn-sm  waves-effect">搜索</button>
                                <a href="#" target="_blank" class="btn btn-success btn-sm waves-effect">导出</a>
                            </div>
                        </div>
                    </div>

                </form>
            </div>


            <div class="card-body table-responsive">
                <table id="data-table-selection" class="table table-striped">
                    <thead>
                    <tr>
                        <th class="select-cell col-lg-1">
                            <div class="checkbox">
                                <label>
                                    <input class="checkbox" type="checkbox" value="" onclick="checkAll(this);">
                                    <i class="input-helper"></i></label>
                            </div>
                        </th>
                        <th>ID</th>
                        <th>直播激活码</th>
                        <th>是否被领取</th>
                        <th>用户昵称</th>
                        <th>激活时间</th>
                        <th>操作</th>
                    </tr>
                    </thead>
                    <tbody>
                    @if(count($vips))
                        @foreach($vips as $item)
                            <tr id="b{{$item->id}}">
                                <td class="select-cell">
                                    <div class="checkbox">
                                        <label>
                                            <input type="checkbox"  name='item_ids'   class="item_id" value="{{ $item->id }}">
                                            <i class="input-helper"></i></label>
                                    </div>
                                </td>
                                <td>{{$item->id}}</td>
                                <td>{{$item->code}}</td>
                                <td>@if($item->is_activated ==1 )未领取@else已领取@endif</td>
                                <td>@if($item->user) 
                                    <a href='/user/show/{{$item->user->id}}'>{{$item->user->nickname}}</a> 
                                    @endif
                                </td>
                                <td>@if($item->user) {{$item->updated_at}} @endif</td>
                                <td>
                                    <!-- <a href="{{ route('vip.edit',['id'=>$item->id]) }}" title="修改">
                                        <button type="button" class="btn bgm-orange waves-effect">
                                            <i class="zmdi zmdi-edit"></i>
                                        </button>
                                    </a> -->
                                    
                                    <button data-id="{{$item->id}}" class="btn btn-info waves-effect sa-warning" title="删除"><i class="zmdi zmdi-close"></i></button>
                                </td>
                            </tr>
                        @endforeach
                    @endif
                    </tbody>
                </table>
                <button class="btn btn-info waves-effect each-item m-l-5 m-b-5" onclick="del_all();">批量删除</button>
                {!! $vips->render() !!}
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script type="text/javascript">
        //全选/反选
        function   checkAll(obj)
        {
            $("#data-table-selection input[type='checkbox']").prop('checked', $(obj).prop('checked'));
        }

        function del_all() {
            var str = $(".item_id");
            var item_num=str.length;
            var ids = '';

            for (var i=0;i<item_num;i++)
            {
                if(str[i].checked == true)
                {
                    ids+=str[i].value+",";
                }
            }

            if(ids == "")
            {
                swal("请先选择要删除的项");
            }else{
                swal({
                    title: "确认要删除选中的项吗?",
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#DD6B55",
                    confirmButtonText: "删除",
                    cancelButtonText: "取消",
                    closeOnConfirm: false
                }, function () {
                    $.ajax({
                        type: 'post',
                        url: '{{route('vip.tv_delete')}}',
                        data: {id: ids},
                        dataType: 'json',
                        success: function (res) {
                            if(res.code == 0){
                                swal(res.message);
                                window.location.href="{{route('vip.tv_index')}}";
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
        $('.sa-warning').click(function () {
            var id = $(this).data('id');
            swal({
                title: "确定删除?",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "删除",
                cancelButtonText: "取消",
                closeOnConfirm: false
            }, function () {
                $.ajax({
                    type: 'post',
                    url: '{{route('vip.tv_delete')}}',
                    data: {id: id},
                    success: function (res) {
                        if (res.code == 0) {
                            $('#b' + id).remove();
                            swal(res.message, "", "success");
                        } else {
                            swal(res.message);
                        }
                    }
                });
            });
        });
        $(function() {
            $('form:first .btn-success:last').click(function() {
                var url = $(this).closest('form').serialize();
                $(this).attr('href', '/vip/tv_index?'+ url +'&export=1');
            });
        });
    </script>
@endsection

