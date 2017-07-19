@extends('layouts.material')
@section('content')
    <div class="container">
        <div class="block-header">
            <h2>留言列表</h2>
        </div>
        <div class="card">
            <div class="card-body table-responsive">
                <table id="data-table-selection" class="table table-striped">
                    <thead>
                    <tr>
                        <th>ID</th>
                        <th class="col-lg-2">用户昵称</th>
                        <th>内容</th>
                        <th>发表时间</th>
                        <th>操作</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach ($words as $item)
                        <tr id="b{{$item->id}}">
                            <td>{{ $item->id }}</td>
                            <td>{{ $item->nickname }} /{{ $item->mobile }}</td>
                            <td>{{ str_limit($item->content,30) }}</td>
                            <td>{{ $item->created_at }}</td>
                            <td>
                                <a href="{{route('leaveword.show',['id'=>$item->id])}}" title="详情"><button class="btn bgm-orange waves-effect"><i class="zmdi zmdi-eye"></i></button></a>
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
                    url: '{{route('leaveword.delete')}}',
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