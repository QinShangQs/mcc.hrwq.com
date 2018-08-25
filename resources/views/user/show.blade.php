@extends('layouts.material')
@section('content')
    <div class="container">
        <div class="block-header">
            <h2><a href="{{route('course.index')}}">信息维护</a> -> 用户详情</h2>
        </div>

        <div class="card">
            <div class="card-body card-padding">
                <div class="row">
                    <div class="col-sm-3">
                        <div class="form-group fg-line ">
                            <label for="exampleInputEmail1">昵称</label>
                            <input type="text" disabled="disabled" value="{{ $user->nickname }}" name="nickname"  class="form-control input-sm">
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-sm-3">
                        <div class="form-group fg-line ">
                            <label for="exampleInputEmail1">微信ID</label>
                            <input type="text" disabled="disabled" value="{{ $user->openid }}" name="openid"  class="form-control input-sm">
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-sm-3">
                        <div class="form-group fg-line ">
                            <label for="exampleInputRealName">真实姓名</label>
                            <input type="text" disabled="disabled" value="{{ $user->realname }}" name="realname"  class="form-control input-sm">
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-3">
                        <div class="form-group fg-line ">
                            <label for="exampleInputEmail1">手机号</label>
                            <input type="text" disabled="disabled" value="{{ $user->mobile }}" name="mobile"  class="form-control input-sm">
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-3">
                        <div class="form-group fg-line ">
                            <label for="exampleInputEmail1">角色</label>
                            <input type="text" disabled="disabled" value="@if($user->role){{ $user_role[$user->role] }}@endif" name="role"  class="form-control input-sm">
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-3">
                        <div class="form-group fg-line ">
                            <label for="exampleInputEmail1">称呼</label>
                            <input type="text" disabled="disabled" value="@if($user->label){{ $user_label[$user->label] }}@endif" name="label"  class="form-control input-sm">
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-3">
                        <div class="form-group fg-line ">
                            <label for="exampleInputEmail1">城市</label>
                            <input type="text" disabled="disabled"  value="@if($user->province){{ $areas[$user->province] }}@endif @if($user->city){{ $areas[$user->city] }}@endif" name="city"  class="form-control input-sm">
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-3">
                        <div class="form-group fg-line ">
                            <label for="exampleInputEmail1">首次登录时间</label>
                            <input type="text" disabled="disabled" value="{{ $user->created_at }}" name="created_at"  class="form-control input-sm">
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-3">
                        <div class="form-group fg-line ">
                            <label for="exampleInputEmail1">注册时间</label>
                            <input type="text" disabled="disabled" value="{{ $user->register_at }}" name="created_at"  class="form-control input-sm">
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-sm-3">
                        <div class="form-group fg-line ">
                            <label for="exampleInputEmail1">成长值</label>
                            <input type="text" disabled="disabled" value="{{ $user->grow }}" name="grow"  class="form-control input-sm">
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-3">
                        <div class="form-group fg-line ">
                            <label for="exampleInputEmail1">是否为和会员</label>
                            <input type="text" disabled="disabled" value="@if($user->vip_flg){{ $user_vip_flg[$user->vip_flg] }}@endif @if(is_vip_forever($user->vip_forever)) 长期 @endif" name="vip_flg"  class="form-control input-sm">
                        </div>
                    </div>
                </div>
                
                @if(is_vip_forever($user->vip_forever) == false)
                <div class="row">
                    <div class="col-sm-3">
                        <div class="form-group fg-line ">
                            <label for="exampleInputEmail1">和会员天数</label>
                            <input type="text" disabled="disabled" value="{{$user->vip_left_day}} ({{vip_left_day_text($user->vip_forever,$user->vip_left_day)}}天) " name="vip_left_day"  class="form-control input-sm">
                        </div>
                    </div>
                </div>
                @endif

                <div class="row">
                    <div class="col-sm-3">
                        <div class="form-group fg-line ">
                            <label for="exampleInputEmail1">和会员激活码</label>
                            <input type="text" disabled="disabled" value="{{ $user->vip_code }}" name="vip_code"  class="form-control input-sm">
                        </div>
                    </div>
                </div>

                @if($lover)
                <div class="row">
                    <div class="col-sm-3">
                        <div class="form-group fg-line ">
                            <label for="exampleInputEmail1">爱心大使用户ID</label>
                            <input type="text" disabled="disabled" value="{{$user->lover_id}}"  class="form-control input-sm">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-3">
                        <div class="form-group fg-line ">
                            <label for="exampleInputEmail1">爱心大使</label>                            
                            <input type="text" disabled="disabled" value="{{$lover->nickname}} / {{$lover->mobile}}"  class="form-control input-sm">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-3">
                        <div class="form-group fg-line ">
                            <label for="exampleInputEmail1">爱心大使关联时间</label>
                            <input type="text" disabled="disabled" value="{{$user->lover_time}}"  class="form-control input-sm">
                        </div>
                    </div>
                </div>
                @endif
                
                <div class="row">
                    <div class="col-sm-3">
                        <div class="form-group fg-line ">
                            <label for="exampleInputEmail1">年龄</label>
                            <input type="text" disabled="disabled" value="{{ $user->age }}" name="age"  class="form-control input-sm">
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-3">
                        <div class="form-group fg-line ">
                            <label for="exampleInputEmail1">生日</label>
                            <input type="text" disabled="disabled" value="{{ $user->birth }}" name="birth"  class="form-control input-sm">
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-3">
                        <div class="form-group fg-line ">
                            <label for="exampleInputEmail1">孩子性别</label>
                            <input type="text" disabled="disabled" value="@if($user->c_sex){{ $user_sex[$user->c_sex] }}@endif" name="c_sex"  class="form-control input-sm">
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-3">
                        <div class="form-group fg-line ">
                            <label for="exampleInputEmail1">孩子生日</label>
                            <input type="text" disabled="disabled" value="{{ $user->c_birth }}" name="c_birth"  class="form-control input-sm">
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-3">
                        <div class="form-group fg-line ">
                            <label>头像</label>
                            @if($user->profileIcon)
                                <span  id="preview_image"  class="row"><img id="img0" src="{{ config('constants.front_url').$user->profileIcon }}" style="width: 200px; width: 200px;"></span>
                            @else
                                <span  id="preview_image"  class="row">暂未上传图片</span>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- 指导师 -->
                @if($user->role == 2)
                <div class="row">
                        <div class="col-sm-3">
                            <div class="form-group fg-line ">
                                <label for="exampleInputEmail1">真实姓名</label>
                                <input type="text" disabled="disabled" value="{{ $user->realname }}" name="realname"  class="form-control input-sm">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-3">
                            <div class="form-group fg-line ">
                                <label for="exampleInputEmail1">头衔</label>
                                <input type="text" disabled="disabled" value="{{ $user->tutor_honor }}" name="tutor_honor"  class="form-control input-sm">
                            </div>
                        </div>
                    </div>
                      <div class="row">
                        <div class="col-sm-3">
                            <div class="form-group fg-line ">
                                <label for="exampleInputEmail1">邮箱</label>
                                <input type="text" disabled="disabled" value="{{ $user->email }}" name="email"  class="form-control input-sm">
                            </div>
                        </div>
                    </div>
                      <div class="row">
                        <div class="col-sm-3">
                            <div class="form-group fg-line ">
                                <label for="exampleInputEmail1">通讯地址</label>
                                <input type="text" disabled="disabled" value="{{ $user->address }}" name="address"  class="form-control input-sm">
                            </div>
                        </div>
                    </div>
                <div class="row">
                    <div class="col-sm-3">
                        <div class="form-group fg-line ">
                            <label for="exampleInputEmail1">价格</label>
                            <input type="text" disabled="disabled" value="{{ $user->tutor_price }}" name="tutor_price"  class="form-control input-sm">
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-3">
                        <div class="form-group fg-line ">
                            <label for="exampleInputEmail1">简介</label>
                            <textarea class="form-control" disabled="disabled" rows="5" placeholder="" name="tutor_introduction">{{ $user->tutor_introduction }}</textarea>
                        </div>
                    </div>
                </div>
                <div class="row">
                        <div class="col-sm-3">
                            <div class="form-group fg-line ">
                                <label>封面图</label>
                                @if($user->tutor_cover)
                                    <span  id="preview_tutor_cover"  class="row"><img id="img0" src="{{ config('constants.front_url').$user->tutor_cover }}" style="width: 300px; width: 400px;"></span>
                                @else
                                    <span  id="preview_tutor_cover"  class="row">暂未上传图片</span>
                                @endif
                            </div>
                        </div>
                    </div>
                @endif

                <!-- 合伙人 -->
                @if($user->role == 3)
                <div class="row">
                    <div class="col-sm-3">
                        <div class="form-group fg-line ">
                            <label for="exampleInputEmail1">银行信息</label>
                            <textarea class="form-control" disabled="disabled" rows="5" placeholder="" name="bank">
                            开户名：{{$user->open_name}}
                            开户行：{{$user->open_bank}}
                            银行帐号：{{$user->bank_account}}
                            </textarea>
                        </div>
                    </div>
                </div>
                @endif

                <div class="row">
                    <div class="col-sm-3">
                        <div class="form-group fg-line ">
                            <label for="exampleInputEmail1">启用状态</label>
                            <input type="text" disabled="disabled" value="@if($user->block==1)启用@else禁用@endif" name="role"  class="form-control input-sm">
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-sm-3">
                        <div class="form-group fg-line ">
                            <label for="exampleInputEmail1">最后修改时间</label>
                            <input type="text" disabled="disabled" value="{{$user->updated_at}}"  class="form-control input-sm">
                        </div>
                    </div>
                </div>

            </div>
        </div>

    </div>
@endsection
