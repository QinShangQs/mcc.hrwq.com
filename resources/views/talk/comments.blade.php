@extends('layouts.material')
@section('content')
    <div class="container">
        <div class="block-header">
            <h2><a href="{{route('talk.index')}}">互助榜</a> -> 评论列表</h2>
        </div>
        <div class="card">
            <button type="submit" class="btn btn-info waves-effect ">问题  ： {{$talk->title}}      <span class="f-500 c-red"> 评论数 ：  {{$talk->comments->count()}} </span></button>
            <div class="card-body card-padding">

                <form action="{{ route('talk.comments',['id'=>$talk->id]) }}" method='GET'>
                    <div class="row">
                        <div class="col-sm-3">
                            <div class="input-group">
                                <span class="input-group-addon"><i class="zmdi zmdi-account zmdi-hc-fw"></i></span>
                                <div class="fg-line">
                                    <input type="text" class="form-control"  name='answer_user' value="{{ request('answer_user') }}" placeholder="评论人">
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-3">
                            <div class="input-group">
                                <span class="input-group-addon"><i class="zmdi zmdi-calendar"></i></span>
                                <div class="dtp-container fg-line">
                                    <input type="text" class="date-picker form-control" name='s_time' value="{{ request('s_time') }}" placeholder="开始时间">
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="input-group">
                                <span class="input-group-addon"><i class="zmdi zmdi-calendar"></i></span>
                                <div class="dtp-container fg-line">
                                    <input type="text" class="date-picker form-control" name='e_time' value="{{ request('e_time') }}" placeholder="结束时间">
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-3">
                            <div class="input-group">
                                <span class="input-group-addon"></span>
                                <div class="fg-line">
                                    <button type="submit" class="btn btn-primary btn-sm m-t-10 waves-effect">搜索</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>

            <div class="card-body table-responsive">
                <table id="data-table-selection" class="table table-striped bootgrid-table">
                    <thead>
                    <tr>
                        <th class="select-cell">
                            <div class="checkbox">
                                <label>
                                    <input class="checkbox" type="checkbox" value="" onclick="checkAll(this);">
                                    <i class="input-helper"></i></label>
                            </div>
                        </th>
                        <th>ID</th>
                        <th>评论人</th>
                        <th>评论时间</th>
                        <th class="col-lg-6">评论内容</th>
                        <th>赞</th>
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
                            <td>{{ $item->id}}</td>
                            <td>{{ $item->answer_user->nickname}}</td>
                            <td>{{ $item->created_at}}</td>
                            <td>{{ $item->comment_c}}</td>
                            <td>{{ $item->likes}}</td>
                            <td>
                                <button data-id="{{$item->id}}" class="btn btn-info waves-effect sa-warning"><i class="zmdi zmdi-close"></i></button>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
                <button class="btn btn-info waves-effect  each-item m-l-5 m-b-5" onclick="del_all();">批量删除</button>
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
                        url: '{{route('talk.comment_delete')}}',
                        data: {id: ids},
                        dataType: 'json',
                        success: function (res) {
                            if(res.code == 0){
                                swal(res.message);
                                window.location.href="{{route('talk.comments',['id'=>$talk->id])}}";
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
                    url: '{{route('talk.comment_delete')}}',
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
