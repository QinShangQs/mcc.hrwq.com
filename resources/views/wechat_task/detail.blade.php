@extends('layouts.material')
@section('style')
@endsection
@section('content')
<div class="container">
    <div class="block-header">
        <h2><a href="{{route('wechat_task.index')}}">微信任务管理</a>  -> 详情</h2>
    </div>
    <div class="card">
        <div class="card-body card-padding">
            <div class="row">
                <div class="col-sm-8">
                    <div class="form-group fg-line " >
                        <label>模版ID</label>
                        <input readonly type="text"  value="{{ $word->template_id}}" class="form-control input-large">
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-8">
                    <div class="form-group fg-line " >
                        <label>模版名称</label>
                        <input readonly type="text"  value="{{ $word->template_name}}" class="form-control input-large">
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-8">
                    <div class="form-group fg-line ">
                        <label>用户类型</label>
                        <input readonly type="text"  value="{{ $user_types[$word->user_type]}}" class="form-control input-large">
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-8">
                    <div class="form-group fg-line ">
                        <label>链接地址</label>
                        <input readonly type="text" name="url" value="{{ $word->url}}" class="form-control input-large" >
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-8">
                    <div class="form-group ">
                        <label>JSON内容</label>
                        <textarea name="content" class="form-control" placeholder="{}" rows="20">{{ base64_decode($word->content) }}</textarea>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-8" style='padding-left: 0'>
                    <div class="input-group form-group">
                        <span class="input-group-addon"><i class="zmdi zmdi-calendar">运行时间</i></span>
                        <div class="dtp-container fg-line">
                            <input readonly type="text" class="form-control date-time-picker" placeholder=""
                                   name='task_run_time' id='datetimepicker' value="{{ $word->task_run_time }}">
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-8">
                    <div class="form-group fg-line ">
                        <label>微信ID</label>
                        <input readonly type="text"  value="{{ $word->openid }}" class="form-control input-large" placeholder="用户详情中的微信ID" />
                    </div>
                </div>
            </div>
            
            <div class="row">
                <div class="col-sm-8">
                    <div class="form-group fg-line ">
                        <label>创建时间</label>
                        <input readonly type="text"  value="{{ $word->created_at }}" class="form-control input-large"  />
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-8">
                    <div class="form-group fg-line ">
                        <label>运行时间</label>
                        <input readonly type="text"  value="{{ $word->task_run_time }}" class="form-control input-large"  />
                    </div>
                </div>
            </div>
            
            <div class="row">
                <div class="col-sm-8">
                    <div class="form-group fg-line ">
                        <label>结束时间</label>
                        <input readonly type="text"  value="{{ $word->finish_time }}" class="form-control input-large" />
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-8">
                    <div class="form-group fg-line ">
                        <label>消息总数量</label>
                        <input readonly type="text"  value="{{ $word->send_total_num }}" class="form-control input-large" />
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-8">
                    <div class="form-group fg-line ">
                        <label>成功消息数</label>
                        <input readonly type="text"  value="{{ $word->send_success_num }}" class="form-control input-large" />
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('script')

<script type="text/javascript">


</script>
@endsection