@extends('layouts.material')
@section('content')
    <div class="container">
        <div class="block-header">
            <h2>标签列表</h2>
        </div>
        <div class="card">
            <div class="card-header card-padding">
                <form action="{{ route('question.tags') }}" method='GET'>
                    <div class="col-sm-9">
                        <div class="col-sm-6">
                            <div class="input-group">
                                <span class="input-group-addon"><i class="zmdi zmdi-search"></i></span>
                                <div class="fg-line">
                                    <input type="text" class="form-control" placeholder="标签名称" name='search_name'
                                           value="{{ request('search_name') }}">
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <button type="submit" class="btn btn-primary btn-sm  waves-effect">搜索</button>
                        </div>
                    </div>
                </form>
                <a href="{{route('question.tag_create')}}">
                    <button class="btn btn-success btn-sm  waves-effect ">
                        <i class="zmdi zmdi-plus zmdi-hc-fw"></i>添加标签
                    </button>
                </a>
            </div>

            <div class="card-body table-responsive">
                <table id="data-table-selection" class="table table-striped">
                    <thead>
                    <tr>
                        <th>ID</th>
                        <th>标签名称</th>
                        <th>排序</th>
                        <th>问题数(问题榜)</th>
                        <th>帖子数(互助榜)</th>
                        <th>操作</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach ($tags as $item)
                        <tr id="b{{$item->id}}">
                            <td>{{ $item->id }}</td>
                            <td>{{ $item->title }}</td>
                            <td>{{ $item->sort }}</td>
                            <td>
                               <a href="{{route('question.index',['tags'=>$item->id])}}"><button class="btn bgm-red waves-effect">{{$item->questions->count()}}</button> </a>
                            </td>
                            <td>
                                <a href="{{route('talk.index',['tags'=>$item->id])}}"><button class="btn bgm-red waves-effect">{{$item->talks->count()}}</button> </a>
                            </td>
                            <td>
                                <a href="{{route('question.tag_edit',['id'=>$item->id])}}">
                                    <button class="btn bgm-orange waves-effect"><i class="zmdi zmdi-edit"></i></button>
                                </a>
                                <button data-id="{{$item->id}}" class="btn btn-info waves-effect sa-warning"><i class="zmdi zmdi-close"></i></button>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
                {!! $tags->render() !!}
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
                    url: '{{route('question.tag_delete')}}',
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