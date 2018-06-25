@extends('layouts.material')
@section('content')
    <div class="container">
        <div class="block-header">
            <h2>视频课程列表</h2>
        </div>
        <div class="card">
            <div class="card-header card-padding">
                <form action="{{ route('vcourse.index') }}" method='GET'>
                    <div class="row">
                        <div class="col-sm-3">                       
                            <div class="input-group">
                                <span class="input-group-addon"><i class="zmdi zmdi-search"></i></span>
                                <div class="fg-line">
                                        <input type="text" class="form-control" placeholder="课程名称" name='title' value="{{ request('title') }}">
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-3">                       
                            <div class="input-group form-group">
                                <span class="input-group-addon"><i class="zmdi zmdi-calendar"></i></span>
                                    <div class="dtp-container fg-line">
                                    <input type="text" class="form-control date-picker" placeholder="上线时间区间" name='s_time' value="{{ request('s_time') }}">
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-3">                       
                            <div class="input-group form-group">
                                <span class="input-group-addon"><i class="zmdi zmdi-calendar"></i></span>
                                    <div class="dtp-container fg-line">
                                    <input type="text" class="form-control date-picker" placeholder="上线时间区间" name='e_time' value="{{ request('e_time') }}">
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="input-group form-group">
                                <span class="input-group-addon"><i class="zmdi zmdi-caret-down-circle zmdi-hc-fw"></i></span>
                                <div class="dtp-container fg-line">
                                    <div class="select">
                                        <select class="selectpicker" name="agency_id">
                                            <option value="">全部</option>
                                            @foreach ($agencyArr as $k=>$item)
                                                <option value="{{$k}}" @if(request('agency_id')==$k) selected @endif>{{$item}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-3">                       
                            <div class="input-group">
                                <span class="input-group-addon"><i class="zmdi zmdi-search"></i></span>
                                <div class="fg-line">
                                        <input type="text" class="form-control" placeholder="讲师" name='teacher' value="{{ request('teacher') }}">
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-2">                       
                            <div class="input-group">
                                <button type="submit" class="btn btn-primary btn-sm  waves-effect">搜索</button>
                            </div>
                        </div>
                    </div>
                    <br />
                    <a href="{{route('vcourse.create')}}">
                        <span class="btn btn-success btn-sm  waves-effect ">
                            <i class="zmdi zmdi-plus zmdi-hc-fw"></i>添加课程
                        </span>
                    </a>
                </form>
            </div>
            <div class="card-body table-responsive">
                <table id="data-table-selection" class="table table-striped">
                    <thead>
                    <tr>
                        <th>ID</th>
                        <th>课程名称</th>
                        <th>课程类别</th>
                        <th>价格</th>
                        <th>上线时间</th>
                        <th>讲师</th>
                        <th>观看次数</th>
                        <th>订单数</th>
                        <th>课程状态</th>
                        <th>作业总数</th>
                        <th>排序</th>
                        <th>操作</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach ($data as $item)
                        <tr id="b{{$item->id}}">
                            <td>{{ $item->id }}</td>
                            <td>{{ str_limit($item->title,30) }}</td>
                            <td>{{ $item->agency->agency_name }}</td>
                            <td>@if($item->type=='1')免费@else{{ $item->price }}@endif</td>
                            <td>{{ $item->created_at }}</td>
                            <td>{{ $item->teacher }}</td>
                            <td>{{ $item->view_cnt }}</td>
                            <td>{{ $item->num }}</td>
                            <td>@if($item->status){{$status_list[$item->status]}}@endif</td>
                            <td>@if(isset($countArr[$item->id])) {{$countArr[$item->id]}} @else 0 @endif</td>
                            <td>{{ $item->sort }}</td>
                            <td>
                                <a href="{{route('vcourse.show',['id'=>$item->id])}}" title="详情"><button class="btn bgm-orange waves-effect"><i class="zmdi zmdi-eye"></i></button></a>
                                <a href="{{route('vcourse.edit',['id'=>$item->id])}}" title="编辑">
                                    <button class="btn bgm-orange waves-effect"><i class="zmdi zmdi-edit"></i></button>
                                </a>
                                @if($item->status == 1)
                                    <button data-id="{{$item->id}}" class="btn bgm-orange waves-effect release-warning" title="发布"><i class="zmdi zmdi-spellcheck"></i></button>
                                @endif
                                @if($item->status == 2)
                                    <button data-id="{{$item->id}}" class="btn bgm-orange waves-effect off-warning" title="下架"><i class="zmdi zmdi-triangle-down"></i></button>
                                @elseif($item->status == 3 )
                                    <button data-id="{{$item->id}}" class="btn bgm-green waves-effect on-warning" title="上架"><i class="zmdi zmdi-triangle-up"></i></button>
                                @endif
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
                    url: '{{route('vcourse.delete')}}',
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

        // 发布
        $('.release-warning').click(function () {
            var id = $(this).data('id');
            swal({
                title: "确定发布?",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "发布",
                cancelButtonText: "取消",
                closeOnConfirm: false
            }, function () {
                $.ajax({
                    type: 'post',
                    url: '{{route('vcourse.release')}}',
                    data: {id: id},
                    success: function (res) {
                        if (res.code == 0) {
                            success(res.message);
                        } else {
                            swal(res.message);
                        }
                    }
                });
            });
        });

        // 下架
        $('.off-warning').click(function () {
            var id = $(this).data('id');
            swal({
                title: "确定下架?",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "下架",
                cancelButtonText: "取消",
                closeOnConfirm: false
            }, function () {
                $.ajax({
                    type: 'post',
                    url: '{{route('vcourse.off')}}',
                    data: {id: id},
                    success: function (res) {
                        if (res.code == 0) {
                            success(res.message);
                        } else {
                            swal(res.message);
                        }
                    }
                });
            });
        });

        // 上架
        $('.on-warning').click(function () {
            var id = $(this).data('id');
            swal({
                title: "确定上架?",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "上架",
                cancelButtonText: "取消",
                closeOnConfirm: false
            }, function () {
                $.ajax({
                    type: 'post',
                    url: '{{route('vcourse.on')}}',
                    data: {id: id},
                    success: function (res) {
                        if (res.code == 0) {
                            success(res.message);
                        } else {
                            swal(res.message);
                        }
                    }
                });
            });
        });
    </script>
@endsection