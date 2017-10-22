@extends('layouts.material')
@section('content')
    <div class="container">
        <div class="block-header">
            <h2>微信消息列表</h2>
        </div>
        <div class="card">
            <div class="card-body card-padding" >
                 <a href="{{route('wechat_push.create')}}"><button class="btn btn-success btn-sm m-t-10  waves-effect "><i class="zmdi zmdi-plus zmdi-hc-fw"></i>添加</button></a>
            </div>
            <div class="card-body table-responsive">
                <table id="data-table-selection" class="table table-striped">
                    <thead>
                    <tr>
                        <th >文章标题</th>
                        <th>文章链接</th>
                        <th>图片</th>
                        <th>文章简介</th>

                        <th>推送年月日_时</th>
                        <th>消息总数量</th>
                        <th>成功消息数</th>
                        <th>操作</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach ($words as $item)
                        <tr id="b{{$item->id}}">
                            <td>{{ $item->title }}</td>
                            <td><a href="{{ $item->url }}" target="_blank">{{ $item->url }}</a></td>
                            <td>
                            	<a href="{{ $item->picurl }}" target="_blank">
                            		<img src="{{ $item->picurl }}" width="100"/>
                            	</a>
                            </td>
                            <td>{{ $item->description }}</td>
                            <td>{{ $item->push_time }}时</td>
                            <td>{{ $item->send_total }}</td>
                            <td>{{ $item->send_success }}</td>
                            <td>
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
                    url: '{{route('wechat_push.delete')}}',
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