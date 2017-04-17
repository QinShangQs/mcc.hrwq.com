@extends('layouts.material')
@section('content')
    <div class="container">
        <div class="block-header">
            <h2><a href="{{route('coupon')}}">优惠券模板</a>  -> 优惠券发放</h2>
        </div>
        <div class="card">
            <a href="{{route('coupon.show',['id'=>$item_coupon->id])}}">
                <button type="submit" class="btn btn-info waves-effect ">
                    <span class="f-500 c-red">优惠券名称</span>  ： {{$item_coupon->name}} &nbsp; &nbsp; &nbsp; &nbsp;
                    <span class="f-500 c-red">描述</span> ：
                    <small>
                        @if($item_coupon->type == 1)
                            满{{$item_coupon->full_money}}元 减{{$item_coupon->cut_money}}元
                        @else
                            {{$item_coupon->discount}}折
                        @endif

                         / {{$coupon_use_scope[$item_coupon->use_scope]}} 使用
                        / 有效期 @if($item_coupon->available_period_type == 1)
                                {{$item_coupon->available_days}}天
                            @else
                                {{$item_coupon->available_start_time}} 至 {{$item_coupon->available_end_time}}
                            @endif
                    </small>
                </button>
             </a>

            <div class="card-body card-padding">
                <form action="{{ route('coupon.distribute',['id'=>$item_coupon->id]) }}" method='GET'>
                    <div class="row">
                        <div class="col-sm-3">
                            <div class="input-group">
                                <span class="input-group-addon"><i class="zmdi zmdi-caret-down-circle zmdi-hc-fw"></i></span>
                                <div class="fg-line">
                                    <select class="form-control" name="has_mobile" id="has_mobile">
                                        <option value="">注册手机号</option>
                                        <option value="yes" @if(request('has_mobile')=='yes') selected @endif>是</option>
                                        <option value="no" @if(request('has_mobile')=='no') selected @endif>不是</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-3">
                            <div class="input-group">
                                <span class="input-group-addon"><i class="zmdi zmdi-caret-down-circle zmdi-hc-fw"></i></span>
                                <div class="fg-line">
                                    <select class="form-control" name="province" id="select_province">
                                        <option value="">选择省</option>
                                        @foreach (get_province() as $k=>$v)
                                            <option value="{{$k}}" @if(request('province')==$k) selected @endif>{{$v}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-3">
                            <div class="input-group">
                                <span class="input-group-addon"><i class="zmdi zmdi-caret-down-circle zmdi-hc-fw"></i></span>
                                <div class="fg-line">
                                    <select class="form-control" name="city" id="select_city">
                                        <option value="">选择市</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-3">
                            <div class="input-group">
                                <span class="input-group-addon"><i class="zmdi zmdi-search zmdi-hc-fw"></i></span>
                                <div class="fg-line">
                                    <select class="form-control" name="c_sex" >
                                        <option value="">选择性别</option>
                                        @foreach ($user_sex as $k=>$v)
                                            <option value="{{$k}}" @if(request('c_sex')==$v) selected @endif>{{$v}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-3">
                            <div class="input-group">
                                <span class="input-group-addon"><i class="zmdi zmdi-search zmdi-hc-fw"></i></span>
                                <div class="fg-line">
                                    <input type="text" class="form-control"  name='c_age_min' value="{{ request('c_age_min') }}" placeholder="最小年龄">
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-3">
                            <div class="input-group">
                                <span class="input-group-addon"><i class="zmdi zmdi-search zmdi-hc-fw"></i></span>
                                <div class="fg-line">
                                    <input type="text" class="form-control"  name='c_age_max' value="{{ request('c_age_max') }}" placeholder="最大年龄">
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-3">
                            <div class="input-group">
                                <span class="input-group-addon"><i class="zmdi zmdi-search zmdi-hc-fw"></i></span>
                                <div class="fg-line">
                                    <input type="text" class="form-control"  name='grow_min' value="{{ request('grow_min') }}" placeholder="最小成长值">
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-3">
                            <div class="input-group">
                                <span class="input-group-addon"><i class="zmdi zmdi-search zmdi-hc-fw"></i></span>
                                <div class="fg-line">
                                    <input type="text" class="form-control"  name='grow_max' value="{{ request('grow_max') }}" placeholder="最大成长值">
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-3">
                            <div class="input-group">
                                <span class="input-group-addon"><i class="zmdi zmdi-calendar"></i></span>
                                <div class="dtp-container fg-line">
                                    <input type="text" class="date-picker form-control" name='s_time' value="{{ request('s_time') }}" placeholder="注册开始时间">
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="input-group">
                                <span class="input-group-addon"><i class="zmdi zmdi-calendar"></i></span>
                                <div class="dtp-container fg-line">
                                    <input type="text" class="date-picker form-control" name='e_time' value="{{ request('e_time') }}" placeholder="注册结束时间">
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-3">
                           <div class="input-group">
                                <span class="input-group-addon"><i class="zmdi zmdi-account zmdi-hc-fw"></i></span>
                                <div class="fg-line">
                                        <input type="text" class="form-control" placeholder="用户昵称" name='nickname' value="{{ request('nickname') }}">
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
                <table id="data-table-selection" class="table table-striped bootgrid-table">
                    <button id="send_to_selected" class="btn btn-success  waves-effect sa-warning each-item m-l-15 m-b-5">发放给选中的用户</button>
                    <button id="send_to_all" class="btn btn-danger  waves-effect sa-warning each-item m-l-15 m-b-5">发放给所有筛选用户</button>
                    <button id="assign-coupon-btn" class="hidden" data-toggle="modal" data-target="#assign-coupon-modal">发放优惠券</button>
                    <div id="assign-coupon-modal" class="modal fade" role="dialog">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <form id="assign-coupon-form">
                                    <div class="modal-header">
                                        <button type="button" class="close"
                                                data-dismiss="modal">&times;</button>
                                        <h4 class="modal-title">发放优惠券并发送短信提醒</h4>
                                    </div>
                                    <div class="modal-body">
                                        <textarea name="content" id="" rows="5" class="form-control"></textarea>
                                    </div>
                                    <div class="modal-footer">
                                        <input type="hidden" name="ids" value="">
                                        <input type="hidden" name="send_type" value="">
                                        <button id="confirm_send_coupon" type="button" class="btn btn-primary"
                                                data-dismiss="modal">
                                            确定
                                        </button>
                                        <button type="button" class="btn btn-default"
                                                data-dismiss="modal">
                                            取消
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <thead>
                    <tr>
                        <th class="select-cell">
                            <div class="checkbox">
                                <label>
                                    <input  type="checkbox" value="" onclick="checkAll(this);">
                                    <i class="input-helper"></i></label>
                            </div>
                        </th>
                        <th>UID</th>
                        <th>昵称</th>
                        <th>手机号</th>
                        <th>地区</th>
                        <th>孩子年龄</th>
                        <th>孩子性别</th>
                        <th>成长值</th>
                        <th>积分</th>
                        <th>角色</th>
                        <th>会员</th>
                        <th>注册时间</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach ($users as $item)
                        <tr id="b{{$item->id}}">
                            <td class="select-cell">
                                <div class="checkbox">
                                    <label>
                                        <input type="checkbox"  name='item_ids'   class="item_id " value="{{ $item->id }}">
                                        <i class="input-helper"></i></label>
                                </div>
                            </td>
                            <td>{{ $item->id }}</td>
                            <td>{{ $item->nickname }}</td>
                            <td>{{ $item->mobile }}</td>
                            <td>{{ @$item->c_province->area_name}}  {{ @$item->c_city->area_name}}</td>
                            <td>{{ $item->c_age }}</td>
                            <td>{{ @$user_sex[$item->c_sex] }}</td>
                            <td>{{ $item->grow }}</td>
                            <td>{{ $item->score }}</td>
                            <td>{{ $user_role[$item->role] }}</td>
                            <td>   @if($item->vip_flg==1) 否  @else 是    @endif  </td>
                            <td>{{ $item->created_at }}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
                {!! $users->render() !!}
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script type="text/javascript">
        var basicUtil = {};
        basicUtil.cookie = {
            setCookie: function(name, value, expires, path, domain, secure) {
                // set time, it's in milliseconds
                var today = new Date();
                today.setTime(today.getTime());
                var expires_date = new Date(today.getTime() + (expires * 1000));
                var cookieVal = name + "=" + escape(value) +
                        ((expires) ? "; expires=" + expires_date.toGMTString() : "") +
                        ((path) ? "; path=" + path : "; path=/") +
                        ((domain) ? "; domain=" + domain : "; domain=" + basicUtil.url.getDomain()) +
                        ((secure) ? "; secure" : "");
                document.cookie = cookieVal;
                return cookieVal;
            },
            getCookie: function(name) {
                // first we'll split this cookie up into name/value pairs
                // note: document.cookie only returns name=value, not the other components
                var a_all_cookies = document.cookie.split(';');
                var a_temp_cookie = '';
                var cookie_name = '';
                var cookie_value = '';
                var b_cookie_found = false; // set boolean t/f default f

                for (i = 0; i < a_all_cookies.length; i++)
                {
                    // now we'll split apart each name=value pair
                    a_temp_cookie = a_all_cookies[i].split('=');


                    // and trim left/right whitespace while we're at it
                    cookie_name = a_temp_cookie[0].replace(/^\s+|\s+$/g, '');

                    // if the extracted name matches passed check_name
                    if (cookie_name === name)
                    {
                        b_cookie_found = true;
                        // we need to handle case where cookie has no value but exists (no = sign, that is):
                        if (a_temp_cookie.length > 1)
                        {
                            cookie_value = unescape(a_temp_cookie[1].replace(/^\s+|\s+$/g, ''));
                        }
                        // note that in cases where cookie is initialized but no value, null is returned
                        return cookie_value;
                        break;
                    }
                    a_temp_cookie = null;
                    cookie_name = '';
                }
                if (!b_cookie_found)
                {
                    return null;
                }
            },
            getCookieInJson: function(name) {
                var cookieVal = this.getCookie(name);
                return eval('(' + decodeURIComponent(cookieVal) + ')');
            },
            delCookie: function(name, path, domain)
            {
                var cookieVal = name + "=" + ((path) ? ";path=" + path : ";path=/") + ((domain) ? ";domain=" + domain : "; domain=" + basicUtil.url.getDomain()) + ";expires=Thu, 01-Jan-1970 00:00:01 GMT";
                document.cookie = cookieVal;
                return cookieVal;
            }
        };
        basicUtil.url = {
            getUrlArray: function() {
                var url = window.location.href;
                var array = url.split('#');
                url = array[0];
                var array = url.split('?');
                var path = array[0];
                while (true) {
                    var path2 = path.replace('//', '/');
                    if (path === path2) {
                        break;
                    }
                    path = path2;
                }
                var urlArray = path.split('/');
                return urlArray;
            },
            getQueryArray: function() {
                var url = window.location.href;
                var array = url.split('?');
                var query = array[1];
                if (typeof(query) === 'undefined') {
                    return [];
                }
                var queryArray = query.split('&');
                return queryArray;
            },
            getQueryKey: function(key) {
                var queryArray = this.getQueryArray();
                var length = queryArray.length;
                if (length === 0) {
                    return null;
                }
                var i = 0;
                var subArray;
                for (i = 0; i < length; i++) {
                    subArray = queryArray[i].split('=');
                    if (subArray[0] === key) {
                        return subArray[1];
                    }
                }
                return null;
            },
            getDomain: function() {
                var array = this.getUrlArray();
                return array[1];
            },
            getQueryDir: function(index) {
                var array = this.getUrlArray();
                if (!index) {
                    var length = array.length;
                    var i = 0;
                    var queryUrl = '';
                    for (i = 2; i < length; i++) {
                        queryUrl += '/' + array[i];
                    }
                    return queryUrl;
                } else {
                    return array[index + 2];
                }
            }
        };
        //全选/反选
        function checkAll(obj)
        {
            $("#data-table-selection input[type='checkbox']").prop('checked', $(obj).prop('checked'));
        }

        //默认加载
        var selected_city_id = '{!! request("city") !!}';
        select_province(selected_city_id);

        //适用范围改变触发
        $('#select_province').change(function(){
            select_province(selected_city_id);
        });

        function select_province(selected_city_id){
            var province_id = $('#select_province').val();
            $.ajax({
                type: 'post',
                url: '{{route('coupon.select_city')}}',
                data: {province_id: province_id,selected_city_id: selected_city_id},
                success: function (res) {
                    $('#select_city').html(res);
                }
            });
        }

        //发放给所有筛选用户
        $("#send_to_all").click(function(){
            var ids = '{{$user_ids_str}}';

            if(ids == "")
            {
                swal("筛选得到的用户为空");
            }else{
                $('input[name="ids"]').val(ids);
                $('input[name="send_type"]').val('all');
                $('#assign-coupon-btn').click();
            }
        });

        //发放给选中的用户
        $("#send_to_selected").click(function(){
            /*var str = $(".item_id");
            var item_num=str.length;
            var ids = '';

            for (var i=0;i<item_num;i++)
            {
                if(str[i].checked == true)
                {
                    ids+=str[i].value+",";
                }
            }
            */
            var cookies_ids = basicUtil.cookie.getCookie('ids');
            if(cookies_ids =="") {
                swal("请先选择要发放的用户");
            }else{
                ids = cookies_ids;
                $('input[name="ids"]').val(ids);
                $('input[name="send_type"]').val('selected');
                $('#assign-coupon-btn').click();
                clearSelect();
            }
        });

        //弹框确认发放
        $("#confirm_send_coupon").click(function(){
            console.log($("#assign-coupon-form").serialize());
            var ids = $('input[name="ids"]').val();
            var content = $('textarea[name="content"]').val();
            if(!content){
                swal("请填写短信内容。");
                return false;
            }
            $.ajax({
                type: 'post',
                url: '{{route('coupon.distribute_selected')}}',
                data: {"id": ids, "coupon_id":'{{$item_coupon->id}}', "content":content},
                dataType: 'json',
                success: function (res) {
                    if(res.code == 0){
                        swal(res.message);
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
        });

        Array.prototype.remove = function(val) {
            var index = this.indexOf(val);
            if (index > -1) {
                this.splice(index, 1);
            }
        };

        $(document).ready(function(){
            //点击事件，选中input内容加入cookie
            $("#data-table-selection input[type='checkbox']").click(function(){
                var ids = new Array();
                var cookies_ids = basicUtil.cookie.getCookie('ids');
                if(cookies_ids){
                    ids = cookies_ids.split(',');
                }
                $("#data-table-selection input[type='checkbox']").each(function(){
                    if($(this).prop('checked') == true ){
                        var val = $(this).val()
                        if(ids.indexOf(val)==-1){
                            ids.push(val)
                        }
                    }else{
                        var val = $(this).val()
                        ids.remove(val)
                    }
                    basicUtil.cookie.setCookie('ids',ids)
                })
            })
            setDefaultInput()
        })
        //默认将之前已经选中的内容选中状态
        function setDefaultInput(){
            var cookies_ids = basicUtil.cookie.getCookie('ids');
            if(cookies_ids){
                ids = cookies_ids.split(',');
                $("#data-table-selection input[type='checkbox']").each(function(){
                    var val = $(this).val()
                    if(ids.indexOf(val)!=-1 && val!=""){
                        $(this).prop('checked','checked')
                    }else{
                        $(this).prop('checked','')
                    }
                })
            }else{
                $("#data-table-selection input[type='checkbox']").prop('checked','')
            }
        }
        function clearSelect()
        {
            //删除cookie中元素
            basicUtil.cookie.delCookie('ids')
            $("#data-table-selection input[type='checkbox']").each(function(){
                $(this).prop('checked','')
            })
        }

    </script>
@endsection

