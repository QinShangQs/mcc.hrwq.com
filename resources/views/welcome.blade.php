@extends('layouts.material')
@section('content')
    <div class="container">
        <div class="block-header">
            <h1 class="text-center">和润万青后台管理系统</h1>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h2>新增用户统计列表.</h2>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-sm-2">
                                    <div class="input-group">
                                        <span class="input-group-addon"><i class="zmdi zmdi-search"></i></span>
                                        <div class="select">
                                            <select class="selectpicker" name="stat_user_type" id="stat_user_type">
                                                <option value="1">按月统计</option>
                                                <option value="2">按年统计</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-sm-2">
                                    <div class="input-group">
                                        <span class="input-group-addon"><i class="zmdi zmdi-search"></i></span>
                                        <div class=" select">
                                            <select class="selectpicker" name="select_year" id="select_s_year">
                                                @foreach ($limit_years as $item)
                                                    <option value="{{$item}}"
                                                            @if($cur_year==$item) selected @endif>{{$item}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-sm-2" id="select_month">
                                    <div class="input-group">
                                        <span class="input-group-addon"><i class="zmdi zmdi-search"></i></span>
                                        <div class=" select">
                                            <select class="selectpicker" name="select_month" id="select_s_month">
                                                @for ($i = 1; $i <= 12; $i++)
                                                    <option value="{{$i}}" @if($cur_month==$i) selected @endif>{{$i}}月
                                                    </option>
                                                @endfor
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-sm-2" id="select_province">
                                    <div class="input-group">
                                        <span class="input-group-addon"><i class="zmdi zmdi-caret-down-circle"></i></span>
                                        <div class="select">
                                            <select class="selectpicker" name="select_province" id="select_s_province">
                                                <option value="">全部省</option>
                                                @foreach($areaPs as $areaP)
                                                    <option value="{{$areaP->area_id}}"
                                                            @if($areaP->area_id == request('select_province')) selected @endif >{{$areaP->area_name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-sm-2" id="select_city">
                                    <div class="input-group">
                                        <span class="input-group-addon"><i class="zmdi zmdi-caret-down-circle"></i></span>
                                        <div class="select" id="areaC">
                                            <select class="selectpicker" id='select_s_city' name='select_s_city'>
                                                <option value="">全部市</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                            </div>
                            <div id="bar-chart" class="flot-chart"></div>
                            <div class="flc-bar"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row" id="row2">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h2>订单统计列表.</h2>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-sm-2">
                                    <div class="input-group">
                                        <span class="input-group-addon"><i class="zmdi zmdi-calendar"></i></span>
                                        <div class="dtp-container fg-line">
                                            <input type="text" class=" form-control" name='s_time' id="date-start"
                                                   value="{{$cur_day}}" placeholder="开始时间">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-2">
                                    <div class="input-group">
                                        <span class="input-group-addon"><i class="zmdi zmdi-calendar"></i></span>
                                        <div class="dtp-container fg-line">
                                            <input type="text" class=" form-control" name='e_time' id="date-end"
                                                   value="{{ $cur_day}}" placeholder="结束时间">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-2">
                                    <div class="input-group">
                                        <span class="input-group-addon"><i class="zmdi zmdi-search"></i></span>
                                        <div class="select">
                                            <select class="selectpicker" name="stat_order_type"
                                                    id="stat_order_type">
                                                <option value="1">大类</option>
                                                <option value="2">小类</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-2" id="select_province">
                                    <div class="input-group">
                                        <span class="input-group-addon"><i class="zmdi zmdi-caret-down-circle"></i></span>
                                        <div class="select">
                                            <select class="selectpicker" name="select_order_province" id="select_s_order_province">
                                                <option value="">全部省</option>
                                                @foreach($areaPs as $areaP)
                                                    <option value="{{$areaP->area_id}}"
                                                            @if($areaP->area_id == request('select_s_order_province')) selected @endif >{{$areaP->area_name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-2" id="select_order_city">
                                    <div class="input-group">
                                        <span class="input-group-addon"><i class="zmdi zmdi-caret-down-circle"></i></span>
                                        <div class="select" id="areaOrderC">
                                            <select class="selectpicker" id='select_s_order_city' name='select_order_city'>
                                                <option value="">全部市</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-2">
                                    <div class="input-group form-group">
                                        <span class="input-group-addon"></span>
                                        <div class="dtp-container fg-line">
                                            <button type="submit" class="btn btn-primary btn-sm  waves-effect"
                                                    onclick="stat_order();">统计
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div id="order-bar-chart" class="flot-chart"></div>
                            <div class="order-flc-bar"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script src="/vendors/bower_components/flot/jquery.flot.js"></script>
    <script src="/vendors/bower_components/flot/jquery.flot.resize.js"></script>
    <script src="/vendors/bower_components/flot-orderBars/js/jquery.flot.orderBars.js"></script>
    <script src="/vendors/bower_components/flot.curvedlines/curvedLines.js"></script>
    <script src="/vendors/bower_components/flot-orderBars/js/jquery.flot.orderBars.js"></script>

    <!-- 获取城市 -->
    <script type="text/javascript">
        function getcity(obj) {
            var city = "<select class='selectpicker' id='select_s_city' name='select_s_city'><option value=''>全部市</option>";
            var arrcity = new Array();
            arrcity = <?php print_r($arrareaCs); ?>;
            arrcity = arrcity[obj];
            for (var i in arrcity) {
                city += "<option value='" + arrcity[i]['area_id'] + "'>" + arrcity[i]['area_name'] + "</option>";
            }
            city += "</select>";
            document.getElementById("areaC").innerHTML = city;
            $('#select_s_city').selectpicker();
        }

        function get_order_city(obj) {
            var city = "<select class='selectpicker' id='select_s_order_city' name='select_s_order_city'><option value=''>全部市</option>";
            var arrcity = new Array();
            arrcity = <?php print_r($arrareaCs); ?>;
            arrcity = arrcity[obj];
            for (var i in arrcity) {
                city += "<option value='" + arrcity[i]['area_id'] + "'>" + arrcity[i]['area_name'] + "</option>";
            }
            city += "</select>";
            document.getElementById("areaOrderC").innerHTML = city;
            $('#select_s_order_city').selectpicker();
        }
    </script>

    <script type="text/javascript">
        /** 订单统计 **/
        function stat_order() {
            $('.tip_link').remove();
            var s_time = $('#date-start').val();
            var e_time = $('#date-end').val();
            var stat_order_type = $('#stat_order_type').val();
            var select_order_province = $('#select_s_order_province').val();
            var select_order_city = $('#select_s_order_city').val();

            if ($('#order-bar-chart')[0]) {
                $.ajax({
                    type: 'post',
                    url: '{{route('stat.order')}}',
                    data: {s_time: s_time, e_time: e_time, stat_order_type: stat_order_type, order_province: select_order_province, order_city: select_order_city},
                    dataType: 'json',
                    success: function (res) {
                        if (res.code == 0) {
                            orderBarData = res.content;

                            dsSHook = function (plot, canvascontext, series) {/*
                             for (var i = 0; i < series.data.length; i++) { // loop the series
                             var offset = plot.offset(); // offset of canvas to body
                             var dP = series.data[i]; // our data point
                             if (dP[1] != 0) {
                             var pos = plot.p2c({x: dP[0], y: dP[1]}); // position of point
                             var barWidth = plot.p2c({
                             x: dP[0] + series.bars.barWidth,
                             y: dP[1]
                             }).left - pos.left; // calc width of bar
                             pos.left += offset.left;
                             pos.top += offset.top; //adjust position for offset
                             $('<a class="tip_link" href="' + series.url + '"></a>').css({
                             'width': barWidth,
                             'color': 'red',
                             'text-align': 'center',
                             'position': 'absolute',
                             'left': pos.left,
                             'top': pos.top
                             }).text(dP[1]).appendTo("#row2"); // add an absolute div with the number
                             }
                             }*/
                            };

                            somePlot = $.plot($("#order-bar-chart"), orderBarData, {
                                points: {show: true, fill: false},
                                grid: {
                                    borderWidth: 1
                                },
                                yaxis: {
                                    tickColor: '#000',
                                    tickDecimals: 0,
                                    font: {
                                        lineHeight: 13,
                                        style: "normal",
                                        color: "#000"
                                    }
                                },
                                xaxis: {
                                    tickColor: '#fff',
                                    tickDecimals: 0,
                                    ticks: res.tick,
                                    font: {
                                        lineHeight: 13,
                                        style: "normal",
                                        color: "#FF5722"
                                    }
                                },
                                bars: {
                                    show: true,
                                    lineWidth: 0,
                                    fill: true
                                },
                                colors: ["#03A9F4"],
                                hooks: {drawSeries: [dsSHook]}
                            });

                        }
                    }
                });
            }
        }
        $(document).ready(function () {
            $('#date-start').datetimepicker({format: 'YYYY-MM-DD'});
            $('#date-end').datetimepicker({format: 'YYYY-MM-DD'});
            //类型切换
            $("#stat_user_type").change(function () {
                stat_user_type($(this).val());
            });

            stat_user_type($("#stat_user_type").val());
            function stat_user_type(select_id) {
                $('#select_month').hide();
                if (select_id == 1) {
                    $('#select_month').show();
                    stat_user($('#select_s_year').val(), $('#select_s_month').val(), $('#select_s_province').val(), $('#select_s_city').val());
                } else {
                    stat_user($('#select_s_year').val(), 0, $('#select_s_province').val(), $('#select_s_city').val());
                }
            }

            var barData = [];

            stat_user($('#select_s_year').val(), $('#select_s_month').val(), $('#select_s_province').val(), $('#select_s_city').val());

            stat_order();

            //年份统计选择 ajax加载统计
            $('#select_s_year').change(function () {
                if ($('#stat_user_type').val() == 2) {
                    stat_user($(this).val(), 0, $('#select_s_province').val(), $('#select_s_city').val());
                } else {
                    stat_user($(this).val(), $('#select_s_month').val(), $('#select_s_province').val(), $('#select_s_city').val());
                }
            });

            //月份统计选择 ajax加载统计
            $('#select_s_month').change(function () {
                stat_user($('#select_s_year').val(), $(this).val(), $('#select_s_province').val(), $('#select_s_city').val());
            });

            //省份选择
            $('#select_s_province').change(function() {
                getcity($(this).val());
                if ($('#stat_user_type').val() == 2) {
                    stat_user($('#select_s_year').val(), 0, $('#select_s_province').val(), $('#select_s_city').val());
                } else {
                    stat_user($('#select_s_year').val(), $('#select_s_month').val(), $('#select_s_province').val(), $('#select_s_city').val());
                }
            });

            $('#select_s_order_province').change(function() {
                get_order_city($(this).val());
            });

            //城市选择
            $(document).on('change', '#select_s_city', function(){
                if ($('#stat_user_type').val() == 2) {
                    stat_user($('#select_s_year').val(), 0, $('#select_s_province').val(), $('#select_s_city').val());
                } else {
                    stat_user($('#select_s_year').val(), $('#select_s_month').val(), $('#select_s_province').val(), $('#select_s_city').val());
                }
            });

            /** 用户统计 **/
            function stat_user(select_s_year, select_s_month, select_s_province, select_s_city) {
                $('.tip_link_2').remove();

                if ($('#bar-chart')[0]) {
                    $.ajax({
                        type: 'post',
                        url: '{{route('stat.user')}}',
                        data: {select_s_month: select_s_month, select_s_year: select_s_year, select_s_province: select_s_province, select_s_city: select_s_city},
                        dataType: 'json',
                        success: function (res) {
                            if (res.code == 0) {
                                barData = res.content;
                                dsHook = function (plot, canvascontext, series) {/*
                                 for (var i = 0; i < series.data.length; i++) { // loop the series
                                 var offset = plot.offset(); // offset of canvas to body
                                 var dP = series.data[i]; // our data point
                                 if (dP[1] != 0) {
                                 var pos = plot.p2c({x: dP[0], y: dP[1]}); // position of point
                                 var barWidth = plot.p2c({
                                 x: dP[0] + series.bars.barWidth,
                                 y: dP[1]
                                 }).left - pos.left; // calc width of bar
                                 pos.left += offset.left;
                                 pos.top += offset.top; //adjust position for offset
                                 $('<a class="tip_link_2" href="' + series.url + '"></a>').css({
                                 'width': barWidth,
                                 'color': 'red',
                                 'text-align': 'center',
                                 'position': 'absolute',
                                 'left': pos.left,
                                 'top': pos.top
                                 }).text(dP[1]).appendTo("body"); // add an absolute div with the number
                                 }
                                 }*/
                                };

                                somePlot = $.plot($("#bar-chart"), barData, {
                                    points: {show: true, fill: false},
                                    grid: {
                                        borderWidth: 1
                                    },
                                    yaxis: {
                                        tickColor: '#000',
                                        tickDecimals: 0,
                                        font: {
                                            lineHeight: 13,
                                            style: "normal",
                                            color: "#000"
                                        }
                                    },
                                    xaxis: {
                                        tickColor: '#fff',
                                        tickDecimals: 0,
                                        ticks: res.tick,
                                        font: {
                                            lineHeight: 13,
                                            style: "normal",
                                            color: "#FF5722"
                                        }
                                    },
                                    lines: {
                                        show: true
                                    },
                                    colors: ["#4CAF50"],
                                    hooks: {drawSeries: [dsHook]}
                                });

                            }
                        }
                    });
                }
            }
        });
    </script>
@endsection




