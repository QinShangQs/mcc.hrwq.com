@extends('layouts.material')
@section('content')
    <div class="container">
        <div class="block-header">
            <h2>互助榜列表</h2>
        </div>
        <div class="card">
            <div class="card-body card-padding">
                <form action="{{ route('talk.index') }}" method='GET'>
                    <div class="row">
                        <div class="col-sm-2">
                            <div class="input-group">
                                <span class="input-group-addon"><i class="zmdi zmdi-search zmdi-hc-fw"></i></span>
                                <div class="fg-line">
                                    <input type="text" class="form-control"  name='content' value="{{ request('content') }}" placeholder="问题名称">
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-2">
                            <div class="input-group">
                                <span class="input-group-addon"><i class="zmdi zmdi-account zmdi-hc-fw"></i></span>
                                <div class="fg-line">
                                    <input type="text" class="form-control"  name='ask_user' value="{{ request('ask_user') }}" placeholder="提出人">
                                </div>
                            </div>
                        </div>


                        <div class="col-sm-2">
                            <div class="input-group">
                                <span class="input-group-addon"><i class="zmdi zmdi-calendar"></i></span>
                                <div class="dtp-container fg-line">
                                    <input type="text" class="date-picker form-control" name='s_time' value="{{ request('s_time') }}" placeholder="开始时间">
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-2">
                            <div class="input-group">
                                <span class="input-group-addon"><i class="zmdi zmdi-calendar"></i></span>
                                <div class="dtp-container fg-line">
                                    <input type="text" class="date-picker form-control" name='e_time' value="{{ request('e_time') }}" placeholder="结束时间">
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-2">
                            <div class="input-group">
                                <span class="input-group-addon"><i class="zmdi zmdi-caret-down-circle"></i></span>
                                <div class="select">
                                    <select class="selectpicker" name="tags" >
                                        <option value="">所属标签</option>
                                        @foreach ($tags as $item)
                                            <option value="{{$item->id}}" @if(request('tags')==$item->id) selected @endif>{{$item->title}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-2">
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
                <table id="data-table-selection" class="table table-striped">
                    <thead>
                    <tr>
                        <th>ID</th>
                        <th class="col-lg-2">问题名称</th>
                        <th>提出人</th>
                        <th>提问时间</th>
                        <th>标签</th>
                        <th>查看数</th>
                        <th>评论数</th>
                        <th>前台显示</th>
                        <th>排序</th>
                        <th>操作</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach ($data as $item)
                        <tr id="b{{$item->id}}">
                            <td>{{ $item->id }}</td>
                            <td>{{ str_limit($item->title,50) }}</td>
                            <td>{{ $item->ask_user->nickname }}</td>
                            <td>{{ $item->created_at }}</td>
                            <td>
                                @foreach ($item->tags as $tag)
                                    <button class="btn btn-primary btn-sm  waves-effect" > {{ $tag->title }}</button>
                                @endforeach
                            </td>
                            <td>{{ $item->view}}</td>
                            <td>
                                <a title="点击查看" href="{{route('talk.comments',['id'=>$item->id])}}"><button class="btn bgm-red waves-effect">{{ $item->comments->count()}} <i class="zmdi zmdi-redo zmdi-hc-fw"></i> </button> </a>
                            </td>
                            <td>
                                <div class="toggle-switch" data-ts-color="blue">
                                    <input id="ts{{$item->id}}" type="checkbox" hidden="hidden" value="{{$item->id}}" @if(empty($item->deleted_at)) checked @endif >
                                    <label for="ts{{$item->id}}" class="ts-helper"></label>
                                </div>
                            </td>
                            <td>{{ $item->sort }}</td>
                            <td>
                                <a href="{{route('talk.show',['id'=>$item->id])}}"><button class="btn bgm-orange waves-effect"><i class="zmdi zmdi-eye"></i></button></a>
                                <a href="{{route('talk.edit',['id'=>$item->id])}}">
                                    <button class="btn bgm-orange waves-effect"><i class="zmdi zmdi-edit"></i></button>
                                </a>
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
        $(document).ready(function(){
            $('body').on('change', '#data-table-selection input:checkbox', function () {
                if ($(this).is(':checked')) {
                    $.ajax({
                        type: 'post',
                        url: '{{route('talk.restore')}}',
                        data: {id:$(this).val()},
                        dataType: 'json',
                        success: function (res) {
                            if(res.code == 0){
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
                }
                else {
                    $.ajax({
                        type: 'post',
                        url: '{{route('talk.delete')}}',
                        data: {id:$(this).val()},
                        dataType: 'json',
                        success: function (res) {
                            if(res.code == 0){
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
                }
            });
        });

    </script>
@endsection