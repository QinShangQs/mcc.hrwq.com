@extends('layouts.material')
@section('content')
    <div class="container">
        <div class="block-header">
            <h2>评论列表</h2>
        </div>
        <div class="card">

            <div class="card-header card-padding">
                
                    <form action="" method='GET'>
                        <div class="row">
                            <div class="col-sm-2">                       
                                <div class="input-group form-group">
                                    <span class="input-group-addon"><i class="zmdi zmdi-search"></i></span>
                                        <div class="dtp-container fg-line">
                                        <input type="text" class="form-control" placeholder="课程名" name='search_course' value="{{ request('search_course') }}">
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-2">                       
                                <div class="input-group form-group">
                                    <span class="input-group-addon"><i class="zmdi zmdi-search"></i></span>
                                        <div class="dtp-container fg-line">
                                        <input type="text" class="form-control" placeholder="评论人" name='search_user' value="{{ request('search_user') }}">
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-2">                       
                                <div class="input-group form-group">
                                    <span class="input-group-addon"><i class="zmdi zmdi-calendar"></i></span>
                                        <div class="dtp-container fg-line">
                                        <input type="text" class="form-control date-picker" placeholder="评论时间（开始）" name='search_time_s' value="{{ request('search_time_s') }}">
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-2">                       
                                <div class="input-group form-group">
                                    <span class="input-group-addon"><i class="zmdi zmdi-calendar"></i></span>
                                        <div class="dtp-container fg-line">
                                        <input type="text" class="form-control date-picker" placeholder="评论时间（结束）" name='search_time_e' value="{{ request('search_time_e') }}">
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-sm-1">
                                <button type="submit" class="btn btn-primary btn-sm  waves-effect">搜索</button>
                            </div>
                        </div>
                </form>
            </div>


            <div class="card-body table-responsive">
                <table id="data-table-selection" class="table table-striped">
                    <thead>
                    <tr>
                        <th>
                            <div class="checkbox">
                                <label>
                                    <input class="checkbox" type="checkbox" value="" onclick="checkAll(this);">
                                    <i class="input-helper"></i></label>
                            </div>
                        </th>
                        <th>ID</th>
                        <th>课程标题</th>
                        <th>评论人</th>
                        <th>评论内容</th>
                        <th>评论时间</th>
                        <th>点赞数</th>
                        <th>操作</th>
                    </tr>
                    </thead>
                    <tbody>
                    @if(count($course_comments))
                        @foreach($course_comments as $item)
                            <tr id="b{{$item->id}}">
                                <td>
                                    <div class="checkbox">
                                        <label>
                                            <input type="checkbox"  name='item_ids'   class="item_id" value="{{ $item->id }}">
                                            <i class="input-helper"></i></label>
                                    </div>
                                </td>
                                <td>{{$item->id}}</td>
                                <td>{{@$item->course->title}}</td>
                                <td>{{@$item->user->nickname}}</td>
                                <td><div style="max-width: 310px;">{{$item->content}}</div></td>
                                <td>{{$item->created_at}}</td>
                                <td>{{ $item->likes }}</td>
                                <td>
                                     <a href="{{ route('course.comment_show',['id'=>$item->id]) }}" title="详情">
                                        <button class="btn bgm-orange waves-effect"><i
                                                    class="zmdi zmdi-eye"></i>
                                        </button>
                                    </a>
                                    <button data-id="{{$item->id}}" class="btn btn-info waves-effect sa-warning" title="删除"><i class="zmdi zmdi-close"></i></button>
                                </td>
                            </tr>
                        @endforeach
                    @endif
                    </tbody>
                </table>
                <button class="btn btn-info waves-effect each-item m-l-5 m-b-5" onclick="del_all();">批量删除</button>
                {!! $course_comments->render() !!}
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
                        url: '{{route('course.comment_delete')}}',
                        data: {id: ids},
                        dataType: 'json',
                        success: function (res) {
                            if(res.code == 0){
                                swal(res.message);
                                window.location.href="{{route('course.comment')}}";
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
                    url: '{{route('course.comment_delete')}}',
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
