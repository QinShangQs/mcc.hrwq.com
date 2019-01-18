@extends('layouts.material')
@section('content')
<div class="container">
    <div class="block-header">
        <h2>广告管理列表</h2>
    </div>
    <div class="card">
        <div class="card-body card-padding" >
            <a href="{{route('ad.create')}}"><button class="btn btn-success btn-sm m-t-10  waves-effect "><i class="zmdi zmdi-plus zmdi-hc-fw"></i>添加广告</button></a>
        </div>
        <div class="table-responsive">
            <div class="table-responsive">
                <table id="data-table-selection" class="table table-striped">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>图片或预览图</th>
                            <th>显示位置</th>
                            <th>名称</th>
                            <th>跳转地址</th>
                            <th>创建时间</th>
                            <th>前台显示</th>
                            <th>操作</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($datas as $data)
                        <tr id="b{{$data->id}}">
                            <td>{{ $data->id }}</td>
                            <td>
                                @if($data->ad_type == 1)
                                <img src="{{$data->display_url}}" width="100px">
                                @else
                                <img src="{{config('qiniu.DOMAIN').$data->display_url}}?vframe/jpg/offset/1" width="100px">
                                @endif
                            </td>
                            <td><button class="btn bgm-red waves-effect"> {{ $ad_types[$data->ad_type] }}</button></td>
                            <td>{{ $data->title }}</td>
                            <td>{{ $data->redirect_url }}</td>
                            <td>{{ $data->created_at }}</td>
                            <td>
                                <div class="toggle-switch" data-ts-color="blue">
                                    <input id="ts{{$data->id}}" type="checkbox" hidden="hidden" value="{{$data->id}}" @if($data->show_type==1) checked @endif >
                                           <label for="ts{{$data->id}}" class="ts-helper"></label>
                                </div>
                            </td>
                            <td>
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
    $(document).ready(function () {
        $('body').on('change', '#data-table-selection input:checkbox', function () {
            $.ajax({
                type: 'post',
                url: '{{route('ad.show')}}',
                data: {id: $(this).val()},
                dataType: 'json',
                success: function (res) {
                    if (res.code == 0) {
//                                swal(res.message);
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

        });
        
        
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
                    url: '{{route('ad.delete')}}',
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
    });


</script>
@endsection