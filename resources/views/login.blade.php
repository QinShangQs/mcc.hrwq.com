<!DOCTYPE html>
<!--[if IE 9 ]>
<html class="ie9"><![endif]-->
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>和润万青</title>
    <!-- Vendor CSS -->
    <link href="/vendors/bower_components/animate.css/animate.min.css" rel="stylesheet">
    <link href="/vendors/bower_components/material-design-iconic-font/dist/css/material-design-iconic-font.min.css" rel="stylesheet">
    <link href="/css/sweetalert.css" rel="stylesheet">
    <!-- CSS -->
    <link href="/css/app.min.1.css" rel="stylesheet">
    <link href="/css/app.min.2.css" rel="stylesheet">
</head>
<body class="login-content">
<!-- Login -->
<div class="lc-block toggled" id="l-login">
    <form id="login-form">
        <div class="input-group m-b-20">
            <span class="input-group-addon"><i class="zmdi zmdi-account"></i></span>
            <div class="fg-line">
                <input type="text" name="name" class="form-control" placeholder="用户名">
            </div>
        </div>

        <div class="input-group m-b-20">
            <span class="input-group-addon"><i class="zmdi zmdi-lock"></i></span>
            <div class="fg-line">
                <input type="password" name="password" class="form-control" placeholder="密码">
            </div>
        </div>

        <div class="input-group m-b-20">
            <span class="input-group-addon"><i class="zmdi zmdi-image"></i></span>
            <div class="fg-line text-left">
                <input type="text" name="captcha" class="form-control" placeholder="验证码">
            </div>
        </div>
        <div class="input-group m-b-20">
            <span class="input-group-addon">&nbsp;</span>
            <div class="fg-line text-left">
                <img src="{{ Captcha::src() }}" id="captcha" alt="captcha">
            </div>
        </div>

        <div class="clearfix"></div>

        <div class="checkbox">
            <label>
                <input type="checkbox" name="remember" value="1">
                <i class="input-helper"></i>
                保持登陆
            </label>
        </div>
        <button type="button" id="btn-login" class="btn btn-login btn-danger btn-float waves-effect"><i
                    class="zmdi zmdi-arrow-forward"></i></button>
    </form>
</div>

<!-- Older IE warning message -->
<!--[if lt IE 9]>
<div class="ie-warning">
    <h1 class="c-white">Warning!!</h1>
    <p>You are using an outdated version of Internet Explorer, please upgrade <br/>to any of the following web browsers
        to access this website.</p>
    <div class="iew-container">
        <ul class="iew-download">
            <li>
                <a href="http://www.google.com/chrome/">
                    <img src="img/browsers/chrome.png" alt="">
                    <div>Chrome</div>
                </a>
            </li>
            <li>
                <a href="https://www.mozilla.org/en-US/firefox/new/">
                    <img src="img/browsers/firefox.png" alt="">
                    <div>Firefox</div>
                </a>
            </li>
            <li>
                <a href="http://www.opera.com">
                    <img src="img/browsers/opera.png" alt="">
                    <div>Opera</div>
                </a>
            </li>
            <li>
                <a href="https://www.apple.com/safari/">
                    <img src="img/browsers/safari.png" alt="">
                    <div>Safari</div>
                </a>
            </li>
            <li>
                <a href="http://windows.microsoft.com/en-us/internet-explorer/download-ie">
                    <img src="img/browsers/ie.png" alt="">
                    <div>IE (New)</div>
                </a>
            </li>
        </ul>
    </div>
    <p>Sorry for the inconvenience!</p>
</div>
<![endif]-->

<!-- Javascript Libraries -->
<script src="/js/jquery.min.js"></script>
<script src="/js/bootstrap.min.js"></script>
<script src="/vendors/bower_components/Waves/dist/waves.min.js"></script>
<script src="/js/sweetalert.min.js"></script>
<!-- Placeholder for IE9 -->
<!--[if IE 9 ]>
<script src="/vendors/bower_components/jquery-placeholder/jquery.placeholder.min.js"></script>
<![endif]-->
<script src="/js/functions.js"></script>
<script>
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
</script>
<script>
    $(document).ready(function () {
        //处理登陆
        $('#btn-login').click(function () {
            $.ajax({
                type: 'post',
                url: '{{route('admin.auth')}}',
                data: $('#login-form').serialize(),
                dataType: 'json',
                success: function (res) {
                    if(res.code == 0){
                        data =res.data;
                        if (data.redirectTo) {
                            location.href = data.redirectTo;
                        }
                    } else {
                        swal({
                            title: res.message,
                            type: "error"
                        });
                    }
                },
                error: function (res) {
                    var errors = res.responseJSON;
                    for (var o in errors) {
                        swal({
                            title: errors[o][0],
                            type: "error"
                        });
                        $('#captcha').attr('src', '{{url('captcha/default')}}'+'/?'+Math.random());
                        break;
                    }
                }
            });
        });
        //刷新二维码
        $('#captcha').click(function () {
            $(this).attr('src', '{{url('captcha/default')}}'+'/?'+Math.random());
        });
    });
</script>
</body>
</html>