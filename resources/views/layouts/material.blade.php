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
    <link href="/vendors/bower_components/bootstrap-sweetalert/lib/sweet-alert.css" rel="stylesheet">
    <link href="/vendors/bower_components/material-design-iconic-font/dist/css/material-design-iconic-font.min.css" rel="stylesheet">
    <link href="/vendors/bower_components/malihu-custom-scrollbar-plugin/jquery.mCustomScrollbar.min.css" rel="stylesheet">
    <link href="/vendors/bower_components/bootstrap-select/dist/css/bootstrap-select.css" rel="stylesheet">
     <link rel="stylesheet" type="text/css" href="/vendors/bower_components/eonasdan-bootstrap-datetimepicker/build/css/bootstrap-datetimepicker.min.css">
     <link rel="stylesheet" type="text/css" href="/vendors/bootgrid/jquery.bootgrid.min.css">
    @yield('vendor-style')
    <!-- CSS -->
    <link href="/css/app.min.1.css" rel="stylesheet">
    <link href="/css/app.min.2.css" rel="stylesheet">
    @yield('style')
</head>
<body>
@include('layouts.header')
<section id="main">
@include('layouts.menu')
    <section id="content">
        @yield('content')
    </section>
</section>
@include('layouts.footer')

@include('elements.reset')

<!-- Javascript Libraries -->
<script src="/js/jquery.min.js"></script>
<script src="/js/bootstrap.min.js"></script>
<script>
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
</script>
<script src="/vendors/bootstrap-growl/bootstrap-growl.min.js"></script>
<script src="/vendors/bower_components/Waves/dist/waves.min.js"></script>
<script src="/vendors/bower_components/malihu-custom-scrollbar-plugin/jquery.mCustomScrollbar.concat.min.js"></script>
<script src="/vendors/bower_components/bootstrap-sweetalert/lib/sweet-alert.min.js"></script>
<script src="/vendors/bower_components/moment/min/moment.min.js"></script>

 <script src="/vendors/bower_components/bootstrap-select/dist/js/bootstrap-select.js"></script>
 <script src="/vendors/bower_components/eonasdan-bootstrap-datetimepicker/build/js/bootstrap-datetimepicker.min.js"></script>

@yield('vendor-script')

<!-- Placeholder for IE9 -->
<!--[if IE 9 ]>
<script src="/vendors/bower_components/jquery-placeholder/jquery.placeholder.min.js"></script>
<![endif]-->
<script src="/js/functions.js?20161026"></script>
@yield('script')

</body>
</html>