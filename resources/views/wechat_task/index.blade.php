@extends('layouts.material')
@section('content')
    <div class="container">
        <div class="block-header">
            <h2>微信任务列表</h2>
        </div>
        <div class="card">
            <div class="card-body card-padding" >
                 <a href="{{route('wechat_task.create')}}"><button class="btn btn-success btn-sm m-t-10  waves-effect "><i class="zmdi zmdi-plus zmdi-hc-fw"></i>添加</button></a>
            </div>
            <div class="card-body table-responsive">
                <table id="data-table-selection" class="table table-striped">
                    <thead>
                    <tr>
                        <th>模版名称</th>
                        <th>链接地址</th>
                        <th>用户类型</th>
                        <th>运行时间</th>
                        <th>完成时间</th>
                        <th>消息总数量</th>
                        <th>成功消息数</th>
                        <th>操作</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach ($words as $item)
                        <tr id="b{{$item->id}}">
                            <td>{{ $item->template_name }}</td>
                            <td><a href="{{ $item->url }}" target="_blank">{{ $item->url }}</a></td>
                            <td>{{ $user_types[$item->user_type] }}</td>
                            <td>{{ $item->task_run_time }}</td>
                            <td>{{ $item->finish_time }}</td>
                            <td>{{ $item->send_total_num }}</td>
                            <td>{{ $item->send_success_num }}</td>
                            <td>
                                <a href="{{route('wechat_task.detail',['id' => $item->id])}}"  class="btn btn-primary waves-effect" title="详情"><i class="zmdi zmdi-info"></i></a>
                                <button data-id="{{$item->id}}" class="btn btn-info waves-effect sa-warning" title="删除"><i class="zmdi zmdi-close"></i></button>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script type="text/javascript">
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
                    url: '{{route('wechat_task.delete')}}',
                    data: {id: id},
                    success: function (res) {
                        if (res.code == 0) {
                            swal(res.message, "", "success");
                            window.setTimeout(reload,700);
                        } else {
                            swal(res.message);
                        }
                    }
                });
            });
        });
    </script>
@endsection