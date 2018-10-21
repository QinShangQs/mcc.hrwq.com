@extends('layouts.material')
@section('content')
    <div class="container">
        <div class="block-header">
            <h2>合伙人审核列表</h2>
        </div>
        <div class="card">

            <div class="card-header card-padding">
                <form action="" method='GET'>
                        <div class="row">
                            
                            <div class="col-sm-2">                       
                                <div class="input-group form-group">
                                    <span class="input-group-addon"><i class="zmdi zmdi-search"></i></span>
                                        <div class="dtp-container fg-line">
                                        <input type="text" class="form-control" placeholder="昵称" name='search_nickname' value="{{ request('search_nickname') }}">
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-sm-2">                       
                                <div class="input-group form-group">
                                    <span class="input-group-addon"><i class="zmdi zmdi-search"></i></span>
                                        <div class="dtp-container fg-line">
                                        <input type="text" class="form-control" placeholder="姓名" name='search_realname' value="{{ request('search_realname') }}">
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-sm-2">                       
                                <div class="input-group form-group">
                                    <span class="input-group-addon"><i class="zmdi zmdi-search"></i></span>
                                        <div class="dtp-container fg-line">
                                        <input type="text" class="form-control" placeholder="手机号" name='search_mobile' value="{{ request('search_mobile') }}">
                                    </div>
                                </div>
                            </div>

                            <div class="col-sm-2">
                                <div class="input-group form-group">
                                    <span class="input-group-addon"><i class="zmdi zmdi-caret-down-circle zmdi-hc-fw"></i></span>
                                    <div class="dtp-container fg-line">
                                        <div class="select">
                                            <select class="selectpicker" name="search_sex">
                                                <option value="">性别</option>
                                                @foreach($user_sex as $key => $sex)
                                                    <option value="{{$key}}" @if(request('search_sex') == $key) selected @endif >{{$sex}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-sm-2">                       
                                <div class="input-group form-group">
                                    <span class="input-group-addon"><i class="zmdi zmdi-search"></i></span>
                                        <div class="dtp-container fg-line">
                                        <input type="text" class="form-control" placeholder="邮箱" name='search_email' value="{{ request('search_email') }}">
                                    </div>
                                </div>
                            </div>
                            
                        </div>

                        <div class="row">
                            
                            <div class="col-sm-2">                       
                                <div class="input-group form-group">
                                    <span class="input-group-addon"><i class="zmdi zmdi-search"></i></span>
                                        <div class="dtp-container fg-line">
                                        <input type="text" class="form-control" placeholder="通讯地址" name='search_address' value="{{ request('search_address') }}">
                                    </div>
                                </div>
                            </div>

                            <div class="col-sm-2">                       
                                <div class="input-group form-group">
                                    <span class="input-group-addon"><i class="zmdi zmdi-calendar"></i></span>
                                        <div class="dtp-container fg-line">
                                        <input type="text" class="form-control date-picker" placeholder="申请时间（开始）" name='search_time_s' value="{{ request('search_time_s') }}">
                                    </div>
                                </div>
                            </div>

                            <div class="col-sm-2">                       
                                <div class="input-group form-group">
                                    <span class="input-group-addon"><i class="zmdi zmdi-calendar"></i></span>
                                        <div class="dtp-container fg-line">
                                        <input type="text" class="form-control date-picker" placeholder="申请时间（结束）" name='search_time_e' value="{{ request('search_time_e') }}">
                                    </div>
                                </div>
                            </div>


                            <div class="col-sm-2">
                                <button type="submit" class="btn btn-primary btn-sm  waves-effect">搜索</button>
                            </div>
                        </div>
                </form>
            </div>


            <div class="card-body table-responsive">
                <table id="data-table-selection" class="table table-striped">
                    <thead>
                    <tr>
                        <th>ID</th>
                        <th>昵称</th>
                        <th>姓名</th>
                        <th>手机号</th>
                        <th>性别</th>
                        <th>邮箱</th>
                        <th>通讯地址</th>
                        <th>期望城市</th>
                        <th>申请时间</th>
                        <th>申请进度</th>
                        <th>操作</th>
                    </tr>
                    </thead>
                    <tbody>
                    @if(count($user_tutors))
                        @foreach($user_tutors as $item)
                            <tr id="b{{$item->id}}">
                                <td>{{$item->id}}</td>
                                <td>{{@$item->user->nickname}}</td>
                                <td>{{$item->realname}}</td>
                                <td>{{@$item->user->mobile}}</td>
                                <td>@if($item->sex){{$user_sex[$item->sex]}}@endif</td>
                                <td>{{$item->email}}</td>
                                <td>{{$item->address}}</td>
                                <td>{{$item->area_name}}</td>
                                <td>{{$item->created_at}}</td>
                                <td>@if($item->progress){{$partner_apply_progress[$item->progress]}}@endif</td>
                                <td>
                                     <a href="{{ route('user.partner_check',['id'=>$item->id]) }}" title="查看详情及审核">
                                        <button class="btn bgm-orange waves-effect"><i
                                                    class="zmdi zmdi-eye"></i>
                                        </button>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    @endif
                    </tbody>
                </table>
                {!! $user_tutors->render() !!}
            </div>
        </div>
    </div>
@endsection

