@extends('layouts.material')
@section('content')
    <div class="container">
        <div class="block-header">
            <h2>热门搜索列表</h2>
        </div>
        <div class="card">
            <div class="card-header card-padding">
                <form action="{{ route('hot_search') }}" method='GET'>
                    <div class="col-sm-9">
                        <div class="col-sm-3">
                            <div class="input-group">
                                <span class="input-group-addon"><i class="zmdi zmdi-search"></i></span>
                                <div class="fg-line">
                                    <select class="form-control" name="type" placeholder="所属类型">
                                        <option value="">全部</option>
                                        @foreach ($type as $k=>$v)
                                            <option value="{{$k}}" @if(request('type')==$k) selected @endif>{{$v}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <button type="submit" class="btn btn-primary btn-sm  waves-effect">搜索</button>
                        </div>
                    </div>
                </form>
                <a href="{{route('hot_search.create')}}">
                    <button class="btn btn-success btn-sm  waves-effect ">
                        <i class="zmdi zmdi-plus zmdi-hc-fw"></i>添加热门关键词
                    </button>
                </a>
            </div>

            <div class="card-body table-responsive">
                <table id="data-table-selection" class="table table-striped">
                    <thead>
                    <tr>
                        <th>ID</th>
                        <th>关键词</th>
                        <th>所属类型</th>
                        <th>排序</th>
                        <th>操作</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach ($data as $item)
                        <tr id="b{{$item->id}}">
                            <td>{{ $item->id }}</td>
                            <td>{{ $item->title }}</td>
                            <td>
                                <button class="btn bgm-red waves-effect">{{$type[$item->type]}}</button> </a>
                            </td>
                            <td>{{ $item->sort }}</td>
                            <td>
                                <a href="{{route('hot_search.edit',['id'=>$item->id])}}">
                                    <button class="btn bgm-orange waves-effect"><i class="zmdi zmdi-edit"></i></button>
                                </a>
                                <button data-id="{{$item->id}}" class="btn btn-info waves-effect sa-warning"><i class="zmdi zmdi-close"></i></button>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
                {!! $data->render() !!}
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
                    url: '{{route('hot_search.delete')}}',
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