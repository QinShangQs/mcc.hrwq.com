@extends('layouts.material')
@section('style')
@endsection
@section('content')
<div class="container">
    <div class="block-header">
        <h2><a href="{{route('wechat_task.index')}}">微信任务管理</a>  -> 添加</h2>
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

            <form id="post_form" action="{{route('wechat_task.store')}}" method="post" onsubmit="return check()">
                {!! csrf_field() !!}
                <input type='hidden' name='template_name' id='template_name' />
                <div class="row">
                    <div class="col-sm-8">
                        <div class="form-group fg-line ">
                            <label>模版消息</label>
                            <select class="selectpicker" name="template_id" id='template_id'>
                                <option value="">
                                    --请选择类型--
                                <span></span>
                                </option>
                                @foreach($templates as $k=>$v)
                                <option value="{{ $v['template_id'] }}" tname='{{$v['title']}}' tcontent='{{ $v['content'] }}'>
                                    {{$v['title']}}
                                </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-8" >
                        <pre id='show-content'>
                            
                        </pre>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-8">
                        <div class="form-group fg-line ">
                            <label>用户类型</label>
                            <select class="selectpicker" id='user_type' name="user_type">
                                <option value=""  >--请选择类型--</option>
                                @foreach($user_types as $k=>$v)
                                <option value="{{ $k }}" >{{$v}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-8">
                        <div class="form-group fg-line " style='display: none'>
                            <label>标题颜色</label>
                            <input type="text" name="topcolor" value="{{ old('topcolor') }}" class="form-control input-large" placeholder="#333" >
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-8">
                        <div class="form-group fg-line ">
                            <label>链接地址</label>
                            <input type="text" name="url" value="{{ old('url') }}" class="form-control input-large" >
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-8">
                        <div class="form-group ">
                            <label>JSON内容</label>
                            <textarea name="content" class="form-control" placeholder="{}" rows="20">{{ old('content') }}</textarea>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-8" style='padding-left: 0'>
                        <div class="input-group form-group">
                            <span class="input-group-addon"><i class="zmdi zmdi-calendar">运行时间</i></span>
                            <div class="dtp-container fg-line">
                                <input type="text" class="form-control date-time-picker" placeholder=""
                                       name='task_run_time' id='datetimepicker' value="{{ old('task_run_time') }}">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-8">
                        <div class="form-group fg-line ">
                            <label>微信ID</label>
                            <input type="text" name="openid" value="{{ old('openid') }}" class="form-control input-large" placeholder="用户详情中的微信ID" />
                            <button type='button' id='btn-test' class="btn btn-default" style='position: absolute; right: 0;bottom: 0' >测试</button>
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


    (function () {
        $('#template_id').change(function () {
            var option = $(this).find("option:selected");
            $("#template_name").val(option.attr('tname'));
            $("#show-content").text(option.attr('tcontent'));
        });

        $("#datetimepicker").datetimepicker({
            format: 'YYYY-MM-DD HH:mm',
            stepping:10
        });

        $('#btn-test').click(function () {
            var data = $("#post_form").serialize();
            $.post("{{route('wechat_task.test')}}", data, function (json) {
                swal(json.message);
            }, 'json');
        });

    })();


    function check() {
       
        swal({
            title: "提交之前请测试，确定要提交吗?",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "确定",
            cancelButtonText: "取消",
            closeOnConfirm: false
        }, function () {
            document.getElementById('post_form').submit();
        });
        return false;
    }

</script>
@endsection