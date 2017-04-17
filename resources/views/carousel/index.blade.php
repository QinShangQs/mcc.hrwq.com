@extends('layouts.material')
@section('content')
    <div class="container">
        <div class="block-header">
            <h2>轮播图管理列表</h2>
        </div>
        <div class="card">
                <div class="card-body card-padding" >
                       <a href="{{route('carousel.create')}}"><button class="btn btn-success btn-sm m-t-10  waves-effect "><i class="zmdi zmdi-plus zmdi-hc-fw"></i>添加轮播图</button></a>
                </div>
            <div class="table-responsive">
                <div class="table-responsive">
                    <table id="data-table-selection" class="table table-striped">
                        <thead>
                        <tr>
                            <th>ID</th>
                            <th>图片</th>
                            <th>显示位置</th>
                            <th>名称</th>
                            <th>跳转类型</th>
                            <th>排序</th>
                            <th>创建时间</th>
                            <th>添加人</th>
                            <th>最后操作人</th>
                            <th>操作</th>
                        </tr>
                        </thead>
                        <tbody>
                            @foreach ($datas as $data)
                                 <tr id="b{{$data->id}}">
                                    <td>{{ $data->id }}</td>
                                     <td><img src="{{URL::asset($data->image_thumb_url)}}" width="100px"></td>
                                    <td><button class="btn bgm-red waves-effect"> {{ $use_type[$data->use_type] }}</button></td>
                                    <td>{{ $data->title }}</td>
                                    <td>{{ $type[$data->redirect_type]}}</td>
                                     <td>{{ $data->sort }}</td>
                                    <td>{{ $data->created_at }}</td>
                                    <td>{{ $data->add_user->name }}</td>
                                    <td>{{ $data->update_user->name }}</td>
                                    <td>
                                        <a href="{{route('carousel.edit',['id'=>$data->id])}}"><button class="btn bgm-orange waves-effect"><i class="zmdi zmdi-edit"></i></button></a>
                                        <button data-id="{{$data->id}}"class="btn btn-info waves-effect sa-warning"><i class="zmdi zmdi-close"></i></button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

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
                    url: '{{route('carousel.delete')}}',
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