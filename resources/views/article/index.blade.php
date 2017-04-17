@extends('layouts.material')
@section('content')
    <div class="container">
        <div class="block-header">
            <h2>文章管理列表</h2>
        </div>
        <div class="card">
                <div class="card-body card-padding" >
                       <a href="{{route('article.create')}}"><button class="btn btn-success btn-sm m-t-10  waves-effect "><i class="zmdi zmdi-plus zmdi-hc-fw"></i>添加文章</button></a>
                </div>

                <div class="table-responsive">
                    <table id="data-table-selection" class="table table-striped">
                        <thead>
                        <tr>
                            <th>所属</th>
                            <th>标题</th>
                            <th>添加人</th>
                            <th>最后操作人</th>
                            <th>更新时间</th>
                            <th>操作</th>
                        </tr>
                        </thead>
                        <tbody>
                            @foreach ($datas as $data)
                                 <tr id="b{{$data->id}}">
                                     <td><button class="btn bgm-red waves-effect">{{ $type[$data->type]}}</button></td>
                                    <td>{{ $data->title }}</td>
                                    <td>{{ $data->add_user->name }}</td>
                                    <td>{{ $data->update_user->name }}</td>
                                    <td>{{ $data->updated_at }}</td>
                                    <td>
                                        <a href="{{route('article.show',['id'=>$data->id])}}"><button class="btn  bgm-orange waves-effect"><i class="zmdi zmdi-eye"></i></button></a>
                                        <a href="{{route('article.edit',['id'=>$data->id])}}"><button class="btn  bgm-orange waves-effect"><i class="zmdi zmdi-edit"></i></button></a>
                                        <button data-id="{{$data->id}}" class="btn  btn-info waves-effect sa-warning"><i class="zmdi zmdi-close"></i></button>
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
                    url: '{{route('article.delete')}}',
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