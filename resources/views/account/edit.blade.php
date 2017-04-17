@extends('layouts.material');
@section('content')
    <div class="container">
        <div class="block-header">
            <h2>修改账号</h2>
        </div>

        <div class="card">

            <div class="card-header">
            </div>

            <div class="card-body card-padding">
                @if (count($errors) > 0)
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                <form class="form-horizontal" role="form" method="post" action="{{route('account.store')}}">
                    {!! csrf_field() !!}
                    <div class="form-group">
                        <label for="name" class="col-sm-2 control-label">用户名</label>
                        <div class="col-sm-10">
                            <div class="fg-line">
                                <input type="text" class="form-control input-sm" id="name" name="name" value="{{$account->name}}" placeholder="用户名">
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="password" class="col-sm-2 control-label">密码</label>
                        <div class="col-sm-10">

                            <div class="fg-line">
                                <input type="password" class="form-control input-sm" id="password" name="password"
                                       placeholder="密码">
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="admin_type" class="col-sm-2 control-label">账号类型</label>
                        <div class="col-sm-5">
                            <div class="fg-line">
                                <select class="form-control" id="admin_type" name="admin_type">
                                    <option>请选择账号类型</option>
                                    @if($curAdmin->admin_type == \App\Models\Admin::ADMIN_TYPE_SUPER)<option value="2" @if($account->admin_type==2) selected @endif>总部管理员</option>@endif
                                    @if($curAdmin->admin_type == \App\Models\Admin::ADMIN_TYPE_SUPER || $curAdmin->admin_type == \App\Models\Admin::ADMIN_TYPE_BASE_MANAGER)<option value="4" @if($account->admin_type==4) selected @endif>总部后台账号</option>@endif
                                    @if($curAdmin->admin_type == \App\Models\Admin::ADMIN_TYPE_SUPER || $curAdmin->admin_type == \App\Models\Admin::ADMIN_TYPE_BASE_MANAGER)<option value="3" @if($account->admin_type==3) selected @endif>门店管理员</option>@endif
                                    <option value="5" @if($account->admin_type==5) selected @endif>门店后台账号</option>
                                    <option value="6" @if($account->admin_type==6) selected @endif>门店配送员</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    @if($curAdmin->admin_type != \App\Models\Admin::ADMIN_TYPE_STORE_MANAGER)
                        <div class="form-group hide" id="store_id_select">
                            <label for="store_id" class="col-sm-2 control-label">门店</label>
                            <div class="col-sm-5">
                                <div class="fg-line">
                                    <select class="form-control" id="store_id" name="store_id">
                                        <option>请选择门店</option>
                                        @if(count($stores))
                                            @foreach($stores as $item)
                                                <option value="{{$item->id}}" @if($account->store_id == $item->id) selected @endif>{{$item->store_title}}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>
                        </div>
                    @endif
                    <div class="form-group">
                        <div class="col-sm-offset-2 col-sm-10">
                            <button type="submit" class="btn btn-primary btn-sm">添加</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script>
        $('select[name="admin_type"]').change(function () {
            var admin_type = $(this).val();
            if (admin_type == '{{\App\Models\Admin::ADMIN_TYPE_STORE_MANAGER}}'
                    || admin_type == '{{\App\Models\Admin::ADMIN_TYPE_STORE_NORMAL}}'
                    || admin_type == '{{\App\Models\Admin::ADMIN_TYPE_STORE_DELIVERY}}'
            )
            {
                $('#store_id_select').removeClass('hide');
            } else {
                $('#store_id_select').addClass('hide');
            }
        });
    </script>
@endsection