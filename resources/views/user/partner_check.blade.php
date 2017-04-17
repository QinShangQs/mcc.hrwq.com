@extends('layouts.material')
@section('content')
    <div class="container">
        <div class="block-header">
            <h2><a href="{{route('user.index')}}">指导师审核管理</a>  -> 指导师审核</h2>
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
                                <input type="text" disabled="disabled" value="{{ $userpartner->user->nickname }}" name="nickname"  class="form-control input-sm">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-3">
                            <div class="form-group fg-line ">
                                <label for="exampleInputEmail1">姓名</label>
                                <input type="text" disabled="disabled" value="{{ $userpartner->realname }}" name="realname"  class="form-control input-sm">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-3">
                            <div class="form-group fg-line ">
                                <label for="exampleInputEmail1">手机号</label>
                                <input type="text" disabled="disabled" value="{{ $userpartner->user->mobile }}" name="mobile"  class="form-control input-sm">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-3">
                            <div class="form-group fg-line ">
                                <label for="exampleInputEmail1">性别</label>
                                <input type="text" disabled="disabled" value="@if($userpartner->sex){{ $user_sex[$userpartner->sex] }}@endif" name="sex"  class="form-control input-sm">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-3">
                            <div class="form-group fg-line ">
                                <label for="exampleInputEmail1">邮箱</label>
                                <input type="text" disabled="disabled"  value="{{ $userpartner->email }}" name="email"  class="form-control input-sm">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-3">
                            <div class="form-group fg-line ">
                                <label for="exampleInputEmail1">通讯地址</label>
                                <input type="text" disabled="disabled"  value="{{ $userpartner->address }}" name="address"  class="form-control input-sm">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-3">
                            <div class="form-group fg-line ">
                                <label for="exampleInputEmail1">期望城市</label>
                                <input type="text" disabled="disabled"  value="{{ $userpartner->area_name }}" name="city"  class="form-control input-sm">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-3">
                            <div class="form-group fg-line ">
                                <label for="exampleInputEmail1">申请进度</label>
                                <input type="text" disabled="disabled"  value="@if($userpartner->progress){{ $partner_apply_progress[$userpartner->progress] }} @endif" name="progress"  class="form-control input-sm">
                            </div>
                        </div>
                    </div>

                    <div class="row" id="div_fali_cause" style="@if($userpartner->progress != 3) display:none; @endif">
                        <div class="col-sm-3">
                            <div class="form-group fg-line ">
                                <label for="exampleInputEmail1">驳回原因</label>
                                <input type="text" @if($userpartner->progress == 3) disabled="disabled" @endif  value="{{ $userpartner->fali_cause }}" name="fali_cause" id="fali_cause"  class="form-control input-sm">
                            </div> 
                        </div>
                    </div>

                    @if($user_has_partner)
                    <div class="alert alert-danger">
                        <ul>
                            <li>该申请中的期望城市已经有合伙人，昵称为 {{ $user_has_partner->nickname }} ,您可将 {{ $user_has_partner->nickname }} 改为普通用户，也可驳回该申请，提示其另选择期望城市！</li>
                        </ul>
                    </div>
                    @endif

                    <div class="form-group fg-line">
                        <input type="hidden" name="id" id="id" value="{{$userpartner->id}}">
                        @if($userpartner->progress == 1)
                            <button class="btn bgm-cyan waves-effect pass-warning" >资料审核通过</button>
                            <button class="btn bgm-cyan waves-effect reject-cause" >资料审核驳回</button>
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
                    url: '{{route('user.partner_pass')}}',
                    data: {id: id},
                    success: function (res) {
                        if (res.code == 0) {
                            location.href='{{route('user.partner')}}';
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
                    url: '{{route('user.partner_reject')}}',
                    data: {id: id,fali_cause:fali_cause},
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

        // 冻结
        $('.frozen-warning').click(function () {
            var id = $('#id').val();
            swal({
                title: "确定冻结该申请?",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "冻结",
                cancelButtonText: "取消",
                closeOnConfirm: false
            }, function () {
                $.ajax({
                    type: 'post',
                    url: '{{route('user.partner_frozen')}}',
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

    </script>
@endsection

