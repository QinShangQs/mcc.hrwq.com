@extends('layouts.material')
@section('content')
    <div class="container">
        <div class="block-header">
            <h2>优惠券模板列表</h2>
        </div>
        <div class="card">
                <div class="card-header card-padding">
                    <form action="{{ route('coupon') }}" method='GET'>
                        <div class="col-sm-9">
                            <div class="col-sm-3">
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="zmdi zmdi-search"></i></span>
                                    <div class="fg-line">
                                        <input type="text" class="form-control"  name='search_name' value="{{ request('search_name') }}" placeholder="模板名称">
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <button type="submit" class="btn btn-primary btn-sm  waves-effect">搜索</button>
                            </div>
                        </div>
                    </form>
                    <a href="{{route('coupon.create')}}">
                        <button class="btn btn-success btn-sm  waves-effect ">
                            <i class="zmdi zmdi-plus zmdi-hc-fw"></i>添加优惠券
                        </button>
                    </a>
                </div>

                <div class="table-responsive">
                    <table id="data-table-selection" class="table table-striped">
                        <thead>
                        <tr>
                            <th>ID</th>
                            <th>模板名称</th>
                            <th>类型</th>
                            <th>优惠</th>
                            <th>使用范围</th>
                            <th>有效期</th>
                            <th>已发放/获取</th>
                            <th>更新时间</th>
                            <th>操作</th>
                        </tr>
                        </thead>
                        <tbody>
                            @foreach ($coupons as $item)
                                @if($item->available_period_type==2 && time()>strtotime($item->available_end_time))
                                    <?php $is_expired=1 ?>
                                @else
                                    <?php  $is_expired=0 ?>
                                @endif
                                 <tr id="b{{$item->id}}">
                                     <td>{{ $item->id }}</td>
                                     <td>{{ $item->name }}</td>
                                     <td><button class="btn  @if($item->type==1) bgm-deeporange  @else  bgm-green  @endif  waves-effect">{{ $type[$item->type]}}</button></td>
                                     <td>
                                         @if($item->type == 1)
                                             满{{$item->full_money}}元 减{{$item->cut_money}}元
                                         @else
                                             {{$item->discount}}折
                                         @endif
                                     </td>
                                    <td>{{$coupon_use_scope[$item->use_scope]}}</td>
                                    <td  @if($is_expired == 1) class="f-500 c-red" @endif>
                                        @if($item->available_period_type == 1)
                                           {{$item->available_days}}天
                                        @else
                                            {{$item->available_start_time}} 至 {{$item->available_end_time}}
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{route('coupon.record',['coupon_id'=>$item->id])}}" title="点击查看详情">
                                        <button class="btn btn-info waves-effect">{{ $item->coupon_user->count() }}</button>
                                        </a>
                                    </td>
                                    <td>{{ $item->updated_at }}</td>
                                    <td>
                                        @if($is_expired == 0)
                                             <a href="{{route('coupon.distribute',['id'=>$item->id])}}"><button class="btn  bgm-red waves-effect"><i class="zmdi zmdi-sign-in zmdi-hc-fw"></i>发放</button></a>
                                        @endif
                                        <a href="{{route('coupon.show',['id'=>$item->id])}}"><button class="btn  bgm-orange waves-effect"><i class="zmdi zmdi-eye"></i></button></a>
                                        <a href="{{route('coupon.edit',['id'=>$item->id])}}"><button class="btn  bgm-green  waves-effect"><i class="zmdi zmdi-edit"></i></button></a>
                                        <button data-id="{{$item->id}}" class="btn  btn-info waves-effect sa-warning"><i class="zmdi zmdi-close"></i></button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    {!! $coupons->render() !!}
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
                    url: '{{route('coupon.delete')}}',
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