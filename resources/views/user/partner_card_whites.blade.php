@extends('layouts.material')
@section('content')
<div class="container">
    <div class="block-header">
        <h2>合伙人卡片-白名单列表</h2>
        <span style="position: absolute;right: 0;top: 0;">
            <button  class="btn btn-info waves-effect" onclick="create()"><i class="zmdi zmdi-account-add"></i></button>
        </span>
    </div>
    <div class="card">

        <div class="card-body table-responsive">
            <table id="data-table-selection" class="table table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>昵称</th>
                        <th>手机号</th>
                        <th>操作</th>
                    </tr>
                </thead>
                <tbody>
                    @if(count($users))
                    @foreach($users as $item)
                    <tr id="b{{$item->user_id}}">
                        <td>{{$item->user_id}}</td>
                        <td>{{$item->user->nickname}}</td>
                        <td>{{$item->user->mobile}}</td>
                        <td>{{$item->created_at}}</td>
                        <td>
                            <a href="{{ route('user.show',['id'=>$item->user_id]) }}" title="详情">
                                <button class="btn bgm-orange waves-effect"><i
                                        class="zmdi zmdi-eye"></i>
                                </button>
                            </a>

                            <button data-id="{{$item->user_id}}" class="btn btn-info waves-effect sa-warning" title="删除"><i class="zmdi zmdi-close"></i></button>
                        </td>

                    </tr>
                    @endforeach
                    @endif
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
                url: '{{route('user.partner_card_whites_remove')}}',
                data: {user_id: id},
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

    function create() {
       var user_id = prompt("添加用户ID"); 
       if(user_id){
           $.ajax({
                type: 'post',
                url: '{{route('user.partner_card_whites_create')}}',
                data: {user_id: user_id},
                success: function (res) {
                    if (res.code == 0) {
                        swal({title:res.message, 'text' : '', type: "success"}, function(){
                            location.reload();
                        });
                    } else {
                        swal(res.message);
                    }
                }
            }); 
       }
    }

</script>

@endsection
