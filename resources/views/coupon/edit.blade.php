@extends('layouts.material')
@section('content')
    <div class="container">
        <div class="block-header">
            <h2><a href="{{route('coupon')}}">优惠券模板</a>  -> 编辑</h2>
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

                    <form id="post_form" action="{{route('coupon.update',['id'=>$coupon->id])}}" method="post">
                        {!! csrf_field() !!}
                        <div class="row">
                            <div class="col-sm-4">
                                <div class="form-group fg-line ">
                                    <label>模板名称</label>
                                    <input type="text" value="{{$coupon->name}}"  name="name"    class="form-control input-sm" >
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-2">
                                <div class="form-group fg-line ">
                                    <label>类型</label>
                                    <select id="select_type" class="selectpicker" name="type">
                                        <option value=""  >请选择</option>
                                        @foreach($type as $k=>$v)
                                            <option value="{{ $k }}" @if($k==$coupon->type) selected @endif>{{$v}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="row type_b type_b_1">
                            <div class="col-sm-2">
                                <div class="form-group fg-line ">
                                    <label>满多少<small>(单位：元)</small></label>
                                    <input type="text" value="{{$coupon->full_money}}"  name="full_money"   class="form-control input-sm" >
                                </div>
                            </div>
                        </div>

                        <div class="row type_b type_b_1">
                            <div class="col-sm-2">
                                <div class="form-group fg-line ">
                                    <label>减多少<small>(单位：元)</small></label>
                                    <input type="text" value="{{$coupon->cut_money}}"  name="cut_money"   class="form-control input-sm" >
                                </div>
                            </div>
                        </div>

                        <div class="row type_b type_b_2">
                            <div class="col-sm-2">
                                <div class="form-group fg-line ">
                                    <label>折扣<small>(如:95表示95折)</small></label>
                                    <input type="text" value="{{$coupon->discount}}"  name="discount"   class="form-control input-sm" >
                                </div>
                            </div>
                        </div>

                        <div class="row" id="select_scope_div" >
                            <div class="col-sm-2">
                                <div class="form-group fg-line ">
                                    <label>适用范围</label>
                                    <select id="select_scope" class="selectpicker" name="use_scope">
                                        <option value=""  >请选择</option>
                                        @foreach($use_scope as $k=>$v)
                                            <option value="{{ $k }}" @if($k==$coupon->use_scope) selected @endif>{{$v}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-2">
                                <div class="form-group fg-line ">
                                    <label>有效期类型 </label>
                                    <select id="select_period_type" class="selectpicker" name="available_period_type">
                                        <option value=""  >请选择</option>
                                        @foreach($coupon_period_type as $k=>$v)
                                            <option value="{{ $k }}" @if($k==$coupon->available_period_type) selected @endif>{{$v}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="row type_a type_a_1">
                            <div class="col-sm-2">
                                <div class="form-group fg-line ">
                                    <label>有效期天数 <small>(单位：天)</small></label>
                                    <input type="text" value="{{$coupon->available_days}}"  name="available_days"    class="form-control input-sm" >
                                </div>
                            </div>
                        </div>

                        <div class="row type_a type_a_2">
                            <div class="col-sm-2">
                                <div class="form-group fg-line ">
                                    <label>有效期开始时间</label>
                                    <input type="text" value="{{$coupon->available_start_time}}"  name="available_start_time"    class="form-control input-sm date-time-picker" >
                                </div>
                            </div>
                        </div>

                        <div class="row type_a type_a_2">
                            <div class="col-sm-2">
                                <div class="form-group fg-line ">
                                    <label>有效期结束时间</label>
                                    <input type="text" value="{{$coupon->available_end_time}}"  name="available_end_time"    class="form-control input-sm date-time-picker" >
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
        $(function(){
            //有效期类型
            $("#select_period_type").change(function(){
                select_period_type($(this).val());
            });
            select_period_type($("#select_period_type").val());
            function select_period_type(select_scope_id){
                $('.type_a').hide();
                $('.type_a_'+select_scope_id).show();
            }

            //类型
            $("#select_type").change(function(){
                select_type($(this).val());
            });
            select_type($("#select_type").val());
            function select_type(select_scope_id){
                $('.type_b').hide();
                $('.type_b_'+select_scope_id).show();
            }

            //适用范围默认加载
            var selected_old_val = '{!! json_encode($use_scope_val) !!}';
            select_scope_child(selected_old_val);

            //适用范围改变触发
            $('#select_scope').change(function(){
                select_scope_child(selected_old_val);
            })

            //适用范围  6.好课中某一类 7.好课中的某节课 8.好看中的某类9.好看中的某课
            function select_scope_child(selected_id){
                var scope_type = $('#select_scope').val();
                $('#select_scope_child').remove();
                if(scope_type == 6 || scope_type == 8){
                    $.ajax({
                        type: 'post',
                        url: '{{route('coupon.select_agency')}}',
                        data: {ids: selected_id},
                        success: function (res) {
                            $('#select_scope_div').after(res);
                            //重新渲染select效果
                            $('#use_scope_val').selectpicker('refresh');
                        }
                    });
                }else if(scope_type == 7 || scope_type == 9){
                    $.ajax({
                        type: 'post',
                        url: '{{route('coupon.select_course')}}',
                        data: {type_id: scope_type,ids: selected_id},
                        success: function (res) {
                            $('#select_scope_div').after(res);
                            //重新渲染select效果
                            $('#use_scope_val').selectpicker('refresh');
                        }
                    });
                }
            }
        })
    </script>
@endsection

