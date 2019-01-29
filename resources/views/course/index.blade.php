@extends('layouts.material')
@section('content')
    <div class="container">
        <div class="block-header">
            <h2>课程列表</h2>
        </div>
        <div class="card">

            <div class="card-header card-padding">
                <form action="" method='GET'>

                    <div class="row">

                        <div class="col-sm-2">                       
                            <div class="input-group form-group">
                                <span class="input-group-addon"><i class="zmdi zmdi-search"></i></span>
                                    <div class="dtp-container fg-line">
                                    <input type="text" class="form-control" placeholder="课程标题" name='search_mobile' value="{{ request('search_title') }}">
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-2">
                            <div class="input-group form-group">
                                <span class="input-group-addon"><i class="zmdi zmdi-caret-down-circle zmdi-hc-fw"></i></span>
                                <div class="dtp-container fg-line">
                                    <div class="select">
                                        <select class="selectpicker" name="search_type">
                                            <option value="">收费类型</option>
                                            <option value="1" @if(request('search_type') == 1) selected @endif >免费</option>
                                            <option value="2" @if(request('search_type') == 2) selected @endif>付费</option>
                                            <option value="3" @if(request('search_type') == 3) selected @endif>团购</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                         <div class="col-sm-2">
                            <div class="input-group form-group">
                                <span class="input-group-addon"><i class="zmdi zmdi-caret-down-circle zmdi-hc-fw"></i></span>
                                <div class="dtp-container fg-line">
                                    <div class="select">
                                        <select class="selectpicker" name="search_city">
                                            <option value="">城市</option>
                                            @foreach($partnerCitys as $city)
                                                <option value="{{$city->area_id}}" @if(request('search_city') == $city->area_id) selected @endif >{{$city->area_name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-2">
                            <div class="input-group form-group">
                                <span class="input-group-addon"><i class="zmdi zmdi-caret-down-circle zmdi-hc-fw"></i></span>
                                <div class="dtp-container fg-line">
                                    <div class="select">
                                        <select class="selectpicker" name="search_agency_id">
                                            <option value="">全部课程类别</option>
                                            @foreach ($agencyArr as $k=>$item)
                                                <option value="{{$k}}" @if(request('search_agency_id')==$k) selected @endif>{{$item}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-2">
                            <div class="input-group form-group">
                                <span class="input-group-addon"><i class="zmdi zmdi-caret-down-circle zmdi-hc-fw"></i></span>
                                <div class="dtp-container fg-line">
                                    <div class="select">
                                        <select class="selectpicker" name="search_promoter">
                                            <option value="">全部发起人</option>
                                            @foreach ($partners as $item)
                                                <option value="{{$item->id}}" @if(request('search_promoter') == $item->id) selected @endif>{{$item->realname}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-2">
                            <div class="input-group form-group">
                                <span class="input-group-addon"><i class="zmdi zmdi-caret-down-circle zmdi-hc-fw"></i></span>
                                <div class="dtp-container fg-line">
                                    <div class="select">
                                        <select class="selectpicker" name="search_status">
                                            <option value="">全部课程状态</option>
                                            @foreach ($status_list as $key=>$item)
                                                <option value="{{$key}}" @if(request('search_status')==$key) selected @endif>{{$item}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-2">
                            <button type="submit" class="btn btn-primary btn-sm  waves-effect">搜索</button>
                            <a href="{{ route('course.create') }}" class="btn btn-success btn-sm  waves-effect ">
                                <i class="zmdi zmdi-plus zmdi-hc-fw"></i>添加课程
                                
                            </a>
                        </div>

                    </div>

                </form>
            </div>


            <div class="card-body table-responsive">
                <table id="data-table-selection" class="table table-striped">
                    <thead>
                    <tr>
                        <th>ID</th>
                        <th>课程标题</th>
                        <th>发起人</th>
                        <th>课程类别</th>
                        <th>时间</th>
                        <th>城市</th>
                        <th>单价</th>
                        <th>套餐价</th>
                        <th>课程状态</th>
                        <th>排序</th>
                        <th>操作</th>
                    </tr>
                    </thead>
                    <tbody>
                    @if(count($courses))
                        @foreach($courses as $item)
                            <tr id="b{{$item->id}}">
                                <td>{{$item->id}}</td>
                                <td>{{$item->title}}</td>
                                <td>{{@$item->user->realname}}</td>
                                <td>{{@$item->agency->agency_name}} 
                                    @if(@$item->type == 1)
                                        (免费)
                                    @elseif(@$item->type == 2)
                                        (付费)
                                    @elseif(@$item->type == 1)
                                        (团购)
                                    @endif
                                </td>
                                <td>{{$item->course_date}}</td>
                                <td>{{@$item->area->area_name}}</td>
                                <td>{{$item->price}}</td>
                                <td>{{$item->package_price}}</td>
                                <td>@if($item->status){{$status_list[$item->status]}}@endif</td>
                                <td>{{$item->sort}}</td>
                                <td>
                                     <a href="{{ route('course.show',['id'=>$item->id]) }}" title="详情">
                                        <button class="btn bgm-orange waves-effect"><i
                                                    class="zmdi zmdi-eye"></i>
                                        </button>
                                    </a>

                                    @if($item->status == 1 || $item->status == 2)
                                    <a href="{{ route('course.edit',['id'=>$item->id]) }}" title="修改">
                                        <button type="button" class="btn bgm-orange waves-effect">
                                            <i class="zmdi zmdi-edit"></i>
                                        </button>
                                    </a>
                                    @endif

                                    @if($item->status == 1)
                                    <button data-id="{{$item->id}}" class="btn bgm-orange waves-effect release-warning" title="发布"><i class="zmdi zmdi-spellcheck"></i></button>
                                    @endif

                                    @if($item->status == 2)
                                        <button data-id="{{$item->id}}" class="btn bgm-orange waves-effect off-warning" title="下架"><i class="zmdi zmdi-triangle-down"></i></button>
                                    @elseif($item->status == 3)
                                        <button data-id="{{$item->id}}" class="btn bgm-green waves-effect on-warning" title="上架"><i class="zmdi zmdi-triangle-up"></i></button>
                                    @endif
                                    <button data-id="{{$item->id}}" class="btn btn-info waves-effect sa-warning" title="删除"><i class="zmdi zmdi-close"></i></button>
                                </td>
                            </tr>
                        @endforeach
                    @endif
                    </tbody>
                </table>
                {!! $courses->render() !!}
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script type="text/javascript">
            
            // 删除
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
                        url: '{{route('course.delete')}}',
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
                        url: '{{route('course.release')}}',
                        data: {id: id},
                        success: function (res) {
                            if (res.code == 0) {
                                window.location.reload();
                                swal(res.message, "", "success");
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
                        url: '{{route('course.off')}}',
                        data: {id: id},
                        success: function (res) {
                            if (res.code == 0) {
                                window.location.reload();
                                swal(res.message, "", "success");
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
                        url: '{{route('course.on')}}',
                        data: {id: id},
                        success: function (res) {
                            if (res.code == 0) {
                                window.location.reload();
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