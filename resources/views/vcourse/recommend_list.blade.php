@extends('layouts.material')
@section('content')
    <div class="container">
        <div class="block-header">
            <h2>推荐列表</h2>
        </div>
        <div class="card">
            <div class="card-header card-padding">
                <a href="{{ route('vcourse.recommend_create') }}">
                    <button class="btn btn-success btn-sm  waves-effect "><i
                                class="zmdi zmdi-plus zmdi-hc-fw"></i>添加推荐课程
                    </button>
                </a>
            </div>

            <div class="card-body table-responsive">
                <table id="data-table-selection" class="table table-striped">
                    <thead>
                    <tr>
                        <th>ID</th>
                        <th>课程标题</th>
                        <th>类别</th>
                        <th>单价</th>
                        <th>课程状态</th>
                        <th>操作</th>
                    </tr>
                    </thead>
                    <tbody>
                    @if(count($vcourse))
                        @foreach($vcourse as $item)
                            <tr id="b{{$item->id}}">
                                <td>{{$item->id}}</td>
                                <td>{{$item->title}}</td>
                                <td>@if($item->type){{$type_list[$item->type]}}@endif</td>
                                <td>{{$item->price}}</td>
                                <td>@if($item->status){{$status_list[$item->status]}}@endif</td>
                                <td>
                                    <button data-id="{{$item->id}}" class="btn btn-info waves-effect sa-warning" title="取消推荐"><i class="zmdi zmdi-close"></i></button>
                                </td>
                            </tr>
                        @endforeach
                    @endif
                    </tbody>
                </table>
                {!! $vcourse->render() !!}
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script type="text/javascript">
            $('.sa-warning').click(function () {
            var id = $(this).data('id');
            swal({
                title: "确定要取消推荐?",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "确定",
                cancelButtonText: "取消",
                closeOnConfirm: false
            }, function () {
                $.ajax({
                    type: 'post',
                    url: '{{route('vcourse.recommend_cancel')}}',
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