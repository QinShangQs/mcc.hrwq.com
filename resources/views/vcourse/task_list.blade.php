@extends('layouts.material')
@section('content')
    <div class="container">
        <div class="block-header">
            <h2>视频课程作业列表</h2>
        </div>
        <div class="card">
            <div class="card-header card-padding">
                <form action="{{ route('vcourse.tasks') }}" method='GET'>
                    <div class="row">
                        <div class="col-sm-3">
                            <div class="input-group">
                                <span class="input-group-addon"><i class="zmdi zmdi-search"></i></span>
                                <div class="fg-line">
                                        <input type="text" class="form-control" placeholder="课程名称" name='title' value="{{ request('title') }}">
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="input-group form-group">
                                <span class="input-group-addon"><i class="zmdi zmdi-calendar"></i></span>
                                    <div class="dtp-container fg-line">
                                    <input type="text" class="form-control date-picker" placeholder="提交时间区间" name='s_time' value="{{ request('s_time') }}">
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="input-group form-group">
                                <span class="input-group-addon"><i class="zmdi zmdi-calendar"></i></span>
                                    <div class="dtp-container fg-line">
                                    <input type="text" class="form-control date-picker" placeholder="提交时间区间" name='e_time' value="{{ request('e_time') }}">
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-2">
                            <div class="input-group form-group">
                                <button type="submit" class="btn btn-primary btn-sm  waves-effect">搜索</button>
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
                        <th>课程名称</th>
                        <th>课程作业</th>
                        <th>作业用户</th>
                        <th>作业内容</th>
                        <th>提交日期</th>
                        <th>点赞数</th>
                        <th>操作</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach ($data as $item)
                        <tr id="b{{$item->id}}">
                            <td class="select-cell">
                                <div class="checkbox">
                                    <label>
                                        <input type="checkbox"  name='item_ids'   class="item_id" value="{{ $item->id }}">
                                        <i class="input-helper"></i></label>
                                </div>
                            </td>
                            <td>{{ str_limit(@$item->vcourse->title,30) }}</td>
                            <td>{{ str_limit(@$item->vcourse->work,30) }}</td>
                            <td>{{ str_limit(@$item->user->nickname,30) }}</td>
                            <td>{{ str_limit($item->mark_content,30) }}</td>
                            <td>{{ str_limit($item->created_at,30) }}</td>
                            <td>{{ $item->likes }}</td>
                            <td>
                                <a href="{{route('vcourse.task_show',['id'=>$item->id])}}" title="详情"><button class="btn bgm-orange waves-effect"><i class="zmdi zmdi-eye"></i></button></a>
                                <button data-id="{{$item->id}}" class="btn btn-info waves-effect sa-warning" title="删除"><i class="zmdi zmdi-close"></i></button>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
                <button class="btn btn-info waves-effect each-item m-l-5 m-b-5" onclick="del_all();">批量删除</button>
                {!! $data->render() !!}
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
                        url: '{{route('vcourse.task_delete')}}',
                        data: {id: ids},
                        dataType: 'json',
                        success: function (res) {
                            if(res.code == 0){
                                swal(res.message);
                                window.location.href="{{route('vcourse.tasks')}}";
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
                    url: '{{route('vcourse.task_delete')}}',
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
    </script>
@endsection