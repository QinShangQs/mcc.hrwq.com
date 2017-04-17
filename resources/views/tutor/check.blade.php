@extends('layouts.material')
@section('style')
    <link href="/vendors/webuploader/webuploader.css" rel="stylesheet">
@endsection
@section('content')
    <div class="container">
        <div class="block-header">
            <h2><a href="{{route('user.index')}}">指导师审核管理</a> -> 指导师审核</h2>
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
                <div class="row">
                    <div class="col-sm-3">
                        <div class="form-group fg-line ">
                            <label for="exampleInputEmail1">昵称</label>
                            <input type="text" disabled="disabled" value="{{ $userTutorApply->user->nickname }}"
                                   name="nickname" class="form-control input-sm">
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-3">
                        <div class="form-group fg-line ">
                            <label for="exampleInputEmail1">姓名</label>
                            <input type="text" disabled="disabled" value="{{ $userTutorApply->realname }}"
                                   name="realname" class="form-control input-sm">
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-3">
                        <div class="form-group fg-line ">
                            <label for="exampleInputEmail1">手机号</label>
                            <input type="text" disabled="disabled" value="{{ $userTutorApply->user->mobile }}"
                                   name="mobile" class="form-control input-sm">
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-3">
                        <div class="form-group fg-line ">
                            <label for="exampleInputEmail1">性别</label>
                            <input type="text" disabled="disabled"
                                   value="@if($userTutorApply->sex){{ $user_sex[$userTutorApply->sex] }}@endif"
                                   name="sex" class="form-control input-sm">
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-3">
                        <div class="form-group fg-line ">
                            <label for="exampleInputEmail1">头衔</label>
                            <input type="text" disabled="disabled" value="{{ $userTutorApply->honor }}" name="honor"
                                   class="form-control input-sm">
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-3">
                        <div class="form-group fg-line ">
                            <label for="exampleInputEmail1">邮箱</label>
                            <input type="text" disabled="disabled" value="{{ $userTutorApply->email }}" name="email"
                                   class="form-control input-sm">
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-3">
                        <div class="form-group fg-line ">
                            <label for="exampleInputEmail1">通讯地址</label>
                            <input type="text" disabled="disabled" value="{{ $userTutorApply->address }}" name="address"
                                   class="form-control input-sm">
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-12">
                        <div class="form-group fg-line ">
                            <label for="exampleInputEmail1">封面图片</label>
                            <div class="col-sm-12"><img src="{{front_url($userTutorApply->cover)}}"></div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-3">
                        <div class="form-group fg-line ">
                            <label for="exampleInputEmail1">价格</label>
                            <input type="text" disabled="disabled" value="{{ $userTutorApply->price }}" name="price"
                                   class="form-control input-sm">
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-3">
                        <div class="form-group fg-line ">
                            <label for="exampleInputEmail1">个人介绍</label>
                            <p name="introduction">{{ strip_tags($userTutorApply->introduction) }}</p>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-3">
                        <div class="form-group fg-line ">
                            <label for="exampleInputEmail1">申请进度</label>
                            <input type="text" disabled="disabled"
                                   value="@if($userTutorApply->progress){{ $tutor_apply_progress[$userTutorApply->progress] }} @endif"
                                   name="progress" class="form-control input-sm">
                        </div>
                    </div>
                </div>

                <div class="row" id="div_fali_cause" style="@if($userTutorApply->progress != 3) display:none; @endif">
                    <div class="col-sm-3">
                        <div class="form-group fg-line ">
                            <label for="exampleInputEmail1">资料审核驳回原因</label>
                            <input type="text" @if($userTutorApply->progress == 3) disabled="disabled"
                                   @endif value="{{ $userTutorApply->fali_cause }}" name="fali_cause" id="fali_cause"
                                   class="form-control input-sm">
                        </div>
                    </div>
                </div>

                <div class="form-group fg-line">
                    <input type="hidden" name="id" id="id" value="{{$userTutorApply->id}}">
                    @if($userTutorApply->progress == 1)
                        <button class="btn bgm-cyan waves-effect pass-warning">资料审核通过</button>
                        <button class="btn bgm-cyan waves-effect reject-cause">资料审核驳回</button>
                        <button class="btn bgm-cyan waves-effect reject-warning" style="display: none;">资料审核驳回</button>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script type="text/javascript">
        // 资料审核通过
        $('.pass-warning').click(function () {
            var id = $('#id').val();
            swal({
                title: "确定通过该申请?",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "通过",
                cancelButtonText: "取消",
                closeOnConfirm: false
            }, function () {
                $.ajax({
                    type: 'post',
                    url: '{{route('user.tutor_pass')}}',
                    data: {id: id},
                    success: function (res) {
                        if (res.code == 0) {
                            window.location.reload();
                            swal(res.message, "", "success");
                        } else {
                            swal(res.message);
                        }
                    }
                });
            });
        });


        // 资料审核不通过
        $('.reject-cause').click(function () {
            alert('请填写驳回原因');
            $('#div_fali_cause').show();
            $('.pass-warning').hide();
            $('.reject-cause').hide();
            $('.reject-warning').show();
            $('.frozen-warning').hide();
        });

        $('.reject-warning').click(function () {
            var id = $('#id').val();
            var fali_cause = $('#fali_cause').val();
            swal({
                title: "确定驳回该申请?",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "驳回",
                cancelButtonText: "取消",
                closeOnConfirm: false
            }, function () {
                $.ajax({
                    type: 'post',
                    url: '{{route('user.tutor_reject')}}',
                    data: {id: id, fali_cause: fali_cause},
                    success: function (res) {
                        if (res.code == 0) {
                            window.location.reload();
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

