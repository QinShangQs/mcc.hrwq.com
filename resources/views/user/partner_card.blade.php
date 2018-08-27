@extends('layouts.material')
@section('content')
<div class="container">
    <div class="block-header">
        <h2>合伙人卡片列表</h2>
    </div>
    <div class="card">
        <div class="card-header card-padding">
            <form action="" method='GET'>
                <div class="row">
                    <div class="col-sm-2">
                        <div class="input-group form-group">
                            <span class="input-group-addon"><i class="zmdi zmdi-search"></i></span>
                            <div class="dtp-container fg-line">
                                <input type="text" class="form-control" placeholder="手机号" name='search_mobile'
                                       value="{{ request('search_mobile') }}">
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-2">
                        <div class="input-group form-group">
                            <span class="input-group-addon"><i class="zmdi zmdi-search"></i></span>
                            <div class="dtp-container fg-line">
                                <input type="text" class="form-control" placeholder="昵称/姓名" name='nickname'
                                       value="{{ request('nickname') }}">
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-2 text-right">
                        <button type="submit" class="btn btn-primary btn-sm  waves-effect">搜索</button>
                    </div>
                    <div class="col-sm-2 text-right Right">
                        <button type=button class="btn btn-default btn-sm  waves-effect"
                                onclick="location.href = '{{ route('user.partner_card_whites') }}'"
                                >查看白名单</button>
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
                        <th>微信</th>
                        <th>邮箱</th>
                        <th>网址</th>
                        <th>卡片生成时间</th>
                    </tr>
                </thead>
                <tbody>
                    @if(count($users))
                    @foreach($users as $item)
                    <tr id="b{{$item->user_id}}">
                        <td>{{$item->user_id}}</td>
                        <td>{{$item->user->nickname}}</td>
                        <td>{{$item->user->realname}}</td>
                        <td>{{$item->tel}}</td>
                        <td>{{$item->wechat}}</td>
                        <td>{{$item->email}}</td>
                        <td>{{$item->website}}</td>
                        <td>{{$item->created_at}}</td>
                        <td>
                            <a href="{{ route('user.partner_card_show',['user_id'=>$item->user_id]) }}" title="详情">
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
            {!! $users->render() !!}
        </div>
    </div>
</div>
@endsection

@section('script')

@endsection
