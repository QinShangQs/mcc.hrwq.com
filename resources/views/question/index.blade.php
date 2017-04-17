@extends('layouts.material')
@section('content')
    <div class="container">
        <div class="block-header">
            <h2>问题榜列表</h2>
        </div>
        <div class="card">
            <div class="card-body card-padding">
                <form action="{{ route('question.index') }}" method='GET'>
                    <div class="row">
                         <div class="col-sm-4">
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
                                <span class="input-group-addon"><i class="zmdi zmdi-account zmdi-hc-fw"></i></span>
                                <div class="fg-line">
                                    <input type="text" class="form-control"  name='answer_user' value="{{ request('answer_user') }}" placeholder="被问人">
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
                       </div>
                    <div class="row">
                        <div class="col-sm-2">
                            <div class="input-group">
                                <span class="input-group-addon"><i class="zmdi  zmdi-caret-down-circle"></i></span>
                                <div class="select">
                                    <select class="selectpicker" name="pick_mod" >
                                        <option value="" > 收听次数 </option>
                                        <option value="1" @if(request('pick_mod')==1) selected @endif> 大于 </option>
                                        <option value="2" @if(request('pick_mod')==2) selected @endif> 等于 </option>
                                        <option value="3" @if(request('pick_mod')==3) selected @endif> 小于 </option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-2">
                            <div class="input-group">
                                <span class="input-group-addon"></span>
                                <div class="fg-line">
                                    <input type="text" class="form-control"  name='listener_num' value="{{ request('listener_num') }}" placeholder="收听次数">
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-2">
                            <div class="input-group">
                                <span class="input-group-addon"><i class="zmdi  zmdi-caret-down-circle"></i></span>
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
                                <span class="input-group-addon"><i class="zmdi  zmdi-caret-down-circle"></i></span>
                                <div class="select">
                                    <select class="selectpicker" name="ask_state" >
                                        <option value="">回答状态</option>
                                        @foreach ($answer_state as $k=>$item)
                                            <option value="{{$k}}" @if(request('ask_state')==$k) selected @endif>{{$item}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-2">
                            <div class="input-group form-group">
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
                        <th>被问人</th>
                        <th>提问时间</th>
                        <th>标签</th>
                        <th>问题状态</th>
                        <th>收听次数</th>
                        <th>前台显示</th>
                        <th>排序值</th>
                        <th>操作</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach ($data as $item)
                        <tr id="b{{$item->pay_id}}">
                            <td>{{ $item->pay_id }}</td>
                            <td>{{ str_limit($item->content,50) }}</td>
                            <td>{{ $item->ask_user->nickname }}</td>
                            <td>{{ $item->answer_user->realname }}({{ $item->answer_user->nickname }})</td>
                            <td>{{ $item->created_at}}</td>
                            <td>
                                @foreach ($item->tags as $tag)
                                    <button class="btn btn-primary btn-sm  waves-effect" > {{ $tag->title }}</button>
                                @endforeach
                            </td>
                            @if($item->answer_flg == 1)
                                <td class="f-500 c-red">{{ $answer_state[$item->answer_flg]}}</td>
                            @else
                               <td class="f-500 c-green">{{ $answer_state[$item->answer_flg]}}</td>
                            @endif
                            <td>{{ $item->listener_nums}}</td>
                            <td>
                                <div class="toggle-switch" data-ts-color="blue">
                                    <input id="ts{{$item->pay_id}}" type="checkbox" hidden="hidden" value="{{$item->pay_id}}" @if(empty($item->deleted_at)) checked @endif >
                                    <label for="ts{{$item->pay_id}}" class="ts-helper"></label>
                                </div>
                            </td>
                            <td>{{ $item->sort }}</td>
                            <td>
                                <a href="{{route('question.show',['id'=>$item->pay_id])}}"><button class="btn bgm-orange waves-effect"><i class="zmdi zmdi-eye"></i></button></a>
                                <a href="{{route('question.edit',['id'=>$item->pay_id])}}">
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
                        url: '{{route('question.restore')}}',
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
                        url: '{{route('question.delete')}}',
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
