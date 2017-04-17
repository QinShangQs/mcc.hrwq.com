@extends('layouts.material')
@section('content')
    <div class="container">
        <div class="block-header">
            <h2><a href="{{route('coupon')}}">优惠券模板</a>  -> 用户获取，使用记录</h2>
        </div>
        <div class="card">
            <div class="card-body card-padding">
                <form action="{{ route('coupon.record') }}" method='GET'>
                    <div class="row">

                        <div class="col-sm-3">
                            <div class="input-group">
                                <span class="input-group-addon"><i class="zmdi zmdi-run  zmdi-hc-fw"></i></span>
                                <div class="fg-line">
                                    <input type="text" class="form-control"  name='user_name' value="{{ request('user_name') }}" placeholder="用户昵称">
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-3">
                            <div class="input-group">
                                <span class="input-group-addon"><i class="zmdi zmdi-search zmdi-hc-fw"></i></span>
                                <div class="fg-line">
                                    <input type="text" class="form-control"  name='coupon_name' value="{{ request('coupon_name') }}" placeholder="优惠券名称">
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-3">
                            <div class="input-group">
                                <span class="input-group-addon"><i class="zmdi zmdi-caret-down-circle zmdi-hc-fw"></i></span>
                                <div class="select">
                                    <select  name="coupon_type" class="selectpicker">
                                        <option value=""  >优惠券类型</option>
                                        @foreach($coupon_type as $k=>$v)
                                            <option value="{{ $k }}" @if($k==request('coupon_type')) selected @endif>{{$v}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-3">
                            <div class="input-group">
                                <span class="input-group-addon"><i class="zmdi zmdi-caret-down-circle zmdi-hc-fw"></i></span>
                                <div class="select">
                                    <select  name="coupon_get_from" class="selectpicker">
                                        <option value=""  >优惠券来源</option>
                                        @foreach($coupon_get_from as $k=>$v)
                                            <option value="{{ $k }}" @if($k==request('coupon_get_from')) selected @endif>{{$v}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        </div>
                    <div class="row">
                        <div class="col-sm-3">
                            <div class="input-group">
                                <span class="input-group-addon"><i class="zmdi zmdi-caret-down-circle zmdi-hc-fw"></i></span>
                                <div class="select">
                                    <select  name="coupon_use_scope" class="selectpicker">
                                        <option value=""  >适用范围</option>
                                        @foreach($coupon_use_scope as $k=>$v)
                                            <option value="{{ $k }}" @if($k==request('coupon_use_scope')) selected @endif>{{$v}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-3">
                            <div class="input-group">
                                <span class="input-group-addon"><i class="zmdi zmdi-caret-down-circle zmdi-hc-fw"></i></span>
                                <div class="select">
                                    <select name="coupon_use_status" class="selectpicker">
                                        <option value=""  >优惠券状态</option>
                                        @foreach($coupon_use_status as $k=>$v)
                                            <option value="{{ $k }}" @if($k==request('coupon_use_status')) selected @endif>{{$v}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-3">
                            <div class="input-group">
                                <span class="input-group-addon"><i class="zmdi zmdi-calendar"></i></span>
                                <div class="dtp-container fg-line">
                                    <input type="text" class="date-picker form-control" name='s_time' value="{{ request('s_time') }}" placeholder="获得时间段-开始">
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="input-group">
                                <span class="input-group-addon"><i class="zmdi zmdi-calendar"></i></span>
                                <div class="dtp-container fg-line">
                                    <input type="text" class="date-picker form-control" name='e_time' value="{{ request('e_time') }}" placeholder="获得时间段-截止">
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
                <table id="data-table-selection" class="table table-striped">
                    <thead>
                    <tr>
                        <th>ID</th>
                        <th>用户昵称</th>
                        <th>名称</th>
                        <th>类型</th>
                        {{--<th class="col-lg-3">有效期</th>--}}
                        <th>来源</th>
                        <th>适用范围</th>
                        <th>状态</th>
                        <th>获得时间</th>
                        <th>使用时间</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach ($data as $item)
                        @if($item->c_coupon->available_period_type==2 && time()>strtotime($item->c_coupon->available_end_time))
                            <?php $is_expired=1 ?>
                        @else
                            <?php  $is_expired=0 ?>
                        @endif
                        <tr id="b{{$item->id}}">
                            <td>{{ $item->id }}</td>
                            <td>{{ @$item->c_user->nickname }}</td>
                            <td>
                                <a href="{{route('coupon.show',['id'=>$item->c_coupon->id])}}">
                                     {{ @$item->c_coupon->name }}
                                </a>
                            </td>
                            <td><button class="btn  @if($item->c_coupon->type==1) bgm-deeporange  @else  bgm-green  @endif  waves-effect">{{ $coupon_type[$item->c_coupon->type]}}</button></td>
                            {{--<td  @if($is_expired == 1) class="f-500 c-red" @endif>--}}
                                {{--@if($item->c_coupon->available_period_type == 1)--}}
                                    {{--{{$item->c_coupon->available_days}}天--}}
                                {{--@else--}}
                                    {{--{{$item->c_coupon->available_start_time}} 至 {{$item->c_coupon->available_end_time}}--}}
                                {{--@endif--}}
                            {{--</td>--}}
                            <td>{{ isset($coupon_get_from[$item->come_from])?$coupon_get_from[$item->come_from]:$item->come_from }}</td>

                            <td>{{$coupon_use_scope[$item->c_coupon->use_scope]}}</td>
                            <td  @if($item->is_used == 1) class="f-500 c-blue" @elseif($item->is_used == 2) class="f-500 c-green" @else  class="f-500 c-red" @endif >
                                {{ $coupon_use_status[$item->is_used]}}
                            </td>
                            <td>{{ $item->created_at }}</td>
                            <td>{{ $item->used_at }}</td>
                            <td>
                                <button data-id="{{$item->id}}" class="btn  btn-info waves-effect sa-warning"><i class="zmdi zmdi-close"></i></button>
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
                    url: '{{route('coupon.record_delete')}}',
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
