@extends('layouts.material')
@section('style')
    <link href="/vendors/webuploader/webuploader.css" rel="stylesheet">
@endsection
@section('content')
<style>
.btn-select { 
position: relative; 
display: inline-block; 
width: 225px; 
height:28px; 
margin:0px 0px 10px 0px;
}
</style>
    <div class="container">
        <div class="block-header">
            <h2><a href="{{route('user.index')}}">用户信息管理</a>  -> 编辑用户信息</h2>
        </div>
        <div class="card">
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

                <form id="post_form" action="{{route('user.update',['id'=>$user->id])}}" method="post">
                    {!! csrf_field() !!}
                    <input type="hidden" name="id" id="id" value="{{$user->id}}">

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
                                <label for="exampleInputEmail1">手机号</label>
                                <input type="text" disabled="disabled" value="{{ $user->mobile }}" name="mobile"  class="form-control input-sm">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-3">
                            <div class="form-group fg-line ">
                                <label for="exampleInputEmail1">角色</label>
                                <div class="select">
                                    <select class="selectpicker" name="role">
                                        <option value="">角色</option>
                                        @foreach($user_role as $key => $role)
                                            <option value="{{$key}}" @if($user->role == $key) selected @endif >{{$role}}</option>
                                        @endforeach
                                    </select>
                                </div>
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
                             <label for="exampleInputEmail1">省市</label>
                                <div >
                                    <select class="btn-select province" name="province" id="province">
                                        <option value="">请选择省</option>
                                        @foreach($province_list as  $provinces)
                                            <option value="{{$provinces->area_id}}" @if($user->province == $provinces->area_id) selected @endif >{{$provinces->area_name}}</option>
                                        @endforeach
                                    </select>
                                    <select class="btn-select city" name="city" id="city">
                                        <option value="">请选择市</option>
                                        @foreach($city_list as  $citys)
                                            <option value="{{$citys->area_id}}" @if($user->city == $citys->area_id) selected @endif >{{$citys->area_name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-3">
                            <div class="form-group fg-line ">
                                <label for="exampleInputEmail1">注册时间</label>
                                <input type="text" disabled="disabled" value="{{ $user->created_at }}" name="created_at"  class="form-control input-sm">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-3">
                            <div class="form-group fg-line ">
                                <label for="exampleInputEmail1">成长值</label>
                                <input type="text" value="{{ $user->grow }}" name="grow"  class="form-control input-sm">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-3">
                            <div class="form-group fg-line ">
                                <label for="exampleInputEmail1">是否为和会员</label>
                                <div class="radio m-b-15">
                                    <label class="radio radio-inline m-r-20">
                                        <input type="radio" name="vip_flg" value="1" @if($user->vip_flg == 1) checked @endif >
                                        <i class="input-helper"></i>
                                        否
                                    </label>
                                    
                                    <label class="radio radio-inline m-r-20">
                                        <input type="radio" name="vip_flg" value="2"  @if($user->vip_flg == 2) checked @endif >
                                        <i class="input-helper"></i>
                                        是
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-3">
                            <div class="form-group fg-line ">
                                <label for="exampleInputEmail1">和会员天数<span id="left_day">({{computer_vip_left_day($user->vip_left_day)}})</span></label>
                                @if(computer_vip_left_day($user->vip_left_day) > 0)
                                	<input data-date-format="YYYY-MM-DD" type="text" value="{{ date('Y-m-d',strtotime($user->vip_left_day)) }}" id="vip_left_day" name="vip_left_day"  class="form-control input-sm date-time-picker">
                                @else
                                	<input data-date-format="YYYY-MM-DD" type="text" value="" id="vip_left_day" name="vip_left_day"  class="form-control input-sm date-time-picker">
                                @endif
                                
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-sm-3">
                            <div class="form-group fg-line ">
                                <label for="exampleInputEmail1">和会员激活码</label>
                                <input type="text" value="{{ $user->vip_code }}" name="vip_code"  class="form-control input-sm">
                            </div>
                        </div>
                    </div>
                              
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
                                <label for="exampleInputEmail1">性别</label>
                                <input type="text" disabled="disabled" value="@if($user->sex){{ $user_sex[$user->sex] }} @endif" name="sex"  class="form-control input-sm">
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
                                <input type="text" disabled="disabled" value="@if($user->c_sex){{ $user_sex[$user->c_sex] }} @endif" name="c_sex"  class="form-control input-sm">
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
                                <label for="exampleInputEmail1">排序<small class="c-red">(数字越大越靠前)</small></label>
                                <input type="text" value="{{ $user->sort }}" name="sort"  class="form-control input-sm">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-3">
                            <div class="form-group fg-line ">
                                <label for="exampleInputEmail1">禁用开关</label>
                                <div class="radio m-b-15">
                                    <label class="radio radio-inline m-r-20">
                                        <input type="radio" name="block" value="1" @if($user->block == 1) checked @endif >
                                        <i class="input-helper"></i>
                                        启用
                                    </label>
                                    
                                    <label class="radio radio-inline m-r-20">
                                        <input type="radio" name="block" value="2"  @if($user->block == 2) checked @endif >
                                        <i class="input-helper"></i>
                                        禁用
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group fg-line">
                        <button class="btn bgm-cyan waves-effect" >保存</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script type="text/javascript">
    
	function getDateDiff(startDate,endDate){  
	    var startTime = new Date(Date.parse(startDate.replace(/-/g,   "/"))).getTime();     
	    var endTime = new Date(Date.parse(endDate.replace(/-/g,   "/"))).getTime();     
	    var dates = Math.abs((startTime - endTime))/(1000*60*60*24);     
	    return  dates;    
	}
                    
              
	$(document).ready(function(){
		$("#vip_left_day").blur(function(){
			var today = '{{date('Y-m-d')}}';
			var left_day = $("#vip_left_day").val();
			if(!left_day){
				$("#left_day").text('');
			}else{
				var days = getDateDiff(today, left_day);
				$("#left_day").text('('+days+')');
			}
		});
		
		$("#province").change(function(){
	            if ($('#province').val()==''||$('#province').val()==undefined) {
	            	$('#city').val('');
	            }else{
	            	$.ajax({
                        type: 'post',
                        url: '{{route('user.getcitylist')}}',
                        data: {id: $('#province').val()},
                        success: function (res) {
                        	var citylist = res.data;
                        	var html_str = '<option value="">请选择市</option>';
                        	for(var i = 0;i<citylist.length;i++){
                        		ol_str = '<option value="'+citylist[i].area_id+'">'+citylist[i].area_name+'</option>';
                        		html_str += ol_str;
                        	}
                        	$(".city").html(html_str);
                        }
                    });
	            }
	        });
	    });
	</script>

@endsection