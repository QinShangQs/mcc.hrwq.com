@extends('layouts.material')
@section('content')
    <div class="container">
        <div class="block-header">
            <h2>好问订单列表</h2>
        </div>
        <div class="card">
            <div class="card-header card-padding">
                <form action="{{ route('order.order_question') }}" method='GET'>
                    <div class="row">
                        <div class="col-sm-3">                       
                            <div class="input-group">
                                <span class="input-group-addon"><i class="zmdi zmdi-search"></i></span>
                                <div class="fg-line">
                                        <input type="text" class="form-control" placeholder="问题名称" name='order_name' value="{{ request('order_name') }}">
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-3">                       
                            <div class="input-group">
                                <span class="input-group-addon"><i class="zmdi zmdi-search"></i></span>
                                <div class="fg-line">
                                        <input type="text" class="form-control" placeholder="订单号" name='order_code' value="{{ request('order_code') }}">
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="input-group form-group">
                                <span class="input-group-addon"><i class="zmdi zmdi-caret-down-circle zmdi-hc-fw"></i></span>
                                <div class="dtp-container fg-line">
                                    <div class="select">
                                        <select class="selectpicker" name="pay_type">
                                            <option value="">订单属性</option>
                                            @foreach ($order_type_question as $k=>$item)
                                                <option value="{{$k}}" @if(request('pay_type')==$k) selected @endif>{{$item}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="input-group form-group">
                                <span class="input-group-addon"><i class="zmdi zmdi-caret-down-circle zmdi-hc-fw"></i></span>
                                <div class="dtp-container fg-line">
                                    <div class="select">
                                        <select class="selectpicker" name="answer_flg">
                                            <option value="">回答状态</option>
                                            @foreach ($answer_state as $k=>$item)
                                                <option value="{{$k}}" @if(request('answer_state')==$k) selected @endif>{{$item}}</option>
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
                                <span class="input-group-addon"><i class="zmdi zmdi-account zmdi-hc-fw"></i></span>
                                <div class="fg-line">
                                    <input type="text" class="form-control" placeholder="指导师姓名" name='realname' value="{{ request('realname') }}">
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-3">                       
                            <div class="input-group">
                                <span class="input-group-addon"><i class="zmdi zmdi-phone zmdi-hc-fw"></i></span>
                                <div class="fg-line">
                                        <input type="text" class="form-control" placeholder="提问人手机号" name='consignee_tel' value="{{ request('consignee_tel') }}">
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="input-group">
                                <span class="input-group-addon"><i class="zmdi zmdi-calendar"></i></span>
                                <div class="dtp-container fg-line">
                                    <input type="text" class="date-picker form-control" name='s_time' value="{{ request('s_time') }}" placeholder="时间段-开始">
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="input-group">
                                <span class="input-group-addon"><i class="zmdi zmdi-calendar"></i></span>
                                <div class="dtp-container fg-line">
                                    <input type="text" class="date-picker form-control" name='e_time' value="{{ request('e_time') }}" placeholder="时间段-截止">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-3">
                            <div class="text-right form-group">
                                <button type="submit" class="btn btn-primary btn-sm  waves-effect">搜索</button>
                                <a href="#" target="_blank" class="btn btn-success btn-sm waves-effect">导出</a>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="card-body table-responsive">
                <table id="data-table-selection" class="table table-striped">
                    <thead>
                    <tr>
                        <th>订单号</th>
                        <th>问题名称</th>
                        <th>提问人</th>
                        <th>指导师</th>
                        <th>下单人</th>
                        <th>订单属性</th>
                        <th>回答状态</th>
                        <th>金额<small class="c-red">(元)</small></th> 
                        <th>下单时间</th>
                        <th>问题标签</th>
                        <!-- <th>操作</th> -->
                    </tr>
                    </thead>
                    <tbody>
                    @foreach ($data as $item)
                        <tr id="b{{$item->id}}">
                            <td>{{ $item->order_code }}</td>
                            <td>{{ @str_limit($item->order_name,30) }}</td>
                            <td>{{ @$item->question->ask_user->nickname }}</td>
                            <td>{{ @$item->question->answer_user->realname }}</td>
                            <td>{{ @$item->user->nickname }}</td>
                            <td>{{ @$order_type_question[$item->pay_type] }}</td>
                            <td>@if(@$item->question->answer_flg==2)已回答@else待回答@endif</td>
                            <td><strong class="c-red">{{ $item->price }}</strong></td>
                            <td>{{ $item->created_at }}</td>
                            <td>
                                @if($item->question)
                                    @foreach ($item->question->tags as $tag)
                                        <button class="btn btn-primary btn-sm  waves-effect" > {{ $tag->title }}</button>
                                    @endforeach
                                @endif
                            </td>
                           <!--  <td>
                                <a href="{{route('order.order_question_show',['id'=>$item->id])}}" title="详情"><button class="btn bgm-orange waves-effect"><i class="zmdi zmdi-eye"></i></button></a>
                            </td> -->
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
        $(function() {
            $('form:first .btn-success:last').click(function() {
                var url = $(this).closest('form').serialize();
                $(this).attr('href', '/order/order_question?'+ url +'&export=1');
            });
        });
    </script>
    </script>
@endsection
