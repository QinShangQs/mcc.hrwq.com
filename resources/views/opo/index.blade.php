@extends('layouts.material')
@section('content')
    <div class="container">
        <div class="block-header">
            <h2>信息维护列表</h2>
        </div>
        <div class="card">

            <div class="card-header card-padding">
                <form action="" method='GET'>
                    <div class="col-sm-9">
                        <div class="col-sm-8">
                            <div class="input-group">
                                <span class="input-group-addon"><i class="zmdi zmdi-search"></i></span>
                                <div class="fg-line">
                                    <input type="text" class="form-control" placeholder="产品名称" name='search_title'
                                           value="{{ request('search_title') }}">
                                </div>

                            </div>
                            
                        </div>

                        <div class="col-sm-1">
                            <button type="submit" class="btn btn-primary btn-sm  waves-effect">搜索</button>
                        </div>
                    </div>
                </form>
                    <a href="{{ route('opo.create') }}">
                        <button class="btn btn-success btn-sm  waves-effect "><i
                                    class="zmdi zmdi-plus zmdi-hc-fw"></i>添加产品
                        </button>
                    </a>
            </div>


            <div class="card-body table-responsive">
                <table id="data-table-selection" class="table table-striped">
                    <thead>
                    <tr>
                        <th>ID</th>
                        <th>产品名称</th>
                        <th>价格</th>
                        <th>项目介绍</th>
                        <th>操作</th>
                    </tr>
                    </thead>
                    <tbody>
                    @if(count($opos))
                        @foreach($opos as $item)
                            <tr id="b{{$item->id}}">
                                <td>{{$item->id}}</td>
                                <td>{{$item->title}}</td>
                                <td>{{$item->price}}</td>
                                <td>{{$item->project_intr}}</td>
                                <td>
                                     <a href="{{ route('opo.show',['id'=>$item->id]) }}" title="详情">
                                        <button class="btn bgm-orange waves-effect"><i
                                                    class="zmdi zmdi-eye"></i>
                                        </button>
                                    </a>

                                    <a href="{{ route('opo.edit',['id'=>$item->id]) }}" title="修改">
                                        <button type="button" class="btn bgm-orange waves-effect">
                                            <i class="zmdi zmdi-edit"></i>
                                        </button>
                                    </a>

                                    <button data-id="{{$item->id}}" class="btn btn-info waves-effect sa-warning" title="删除"><i class="zmdi zmdi-close"></i></button>
                                </td>
                            </tr>
                        @endforeach
                    @endif
                    </tbody>
                </table>
                {!! $opos->render() !!}
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
                    url: '{{route('opo.delete')}}',
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