@extends('layouts.material')
@section('style')
    <link href="/vendors/webuploader/webuploader.css" rel="stylesheet">
@endsection
@section('content')
    <div class="container">
        <div class="block-header">
            <h2><a href="{{route('wechat_push.index')}}">微信消息管理</a>  -> 添加</h2>
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

                <form id="post_form" action="{{route('wechat_push.store')}}" method="post">
                    {!! csrf_field() !!}
                    <div class="row">
                        <div class="col-sm-8">
                            <div class="form-group fg-line ">
                                <label>文章标题</label>
                                <input type="text" name="title"  value="{{ old('title') }}"  class="form-control input-large" >
                            </div>
                        </div>
                    </div>
                    <div class="row">
	                    <div class="col-sm-8">
	                         <div class="form-group fg-line ">
	                            <label>文章链接</label>
	                            <input type="text" name="url" value="{{ old('url') }}" class="form-control input-large" >
	                         </div>
	                    </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group fg-line ">
                                <label>图片 <small class="c-red">(限1张,大小限制2MB)</small></label>
                                <div id="uploader" class="wu-example">
                                    <div class="queueList">
                                        <div id="dndArea" class="placeholder">
                                            <div id="filePicker" class="webuploader-container">
                                                <div class="webuploader-pick">点击选择图片</div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="statusBar" style="display: none;">
                                        <div class="progress">
                                            <span class="text"></span>
                                            <span class="percentage"></span>
                                        </div>
                                        <div class="btns">
                                            <div id="filePicker2" class="webuploader-container"></div>
                                        </div>
                                        <div class="uploadBtn state-pedding">开始上传</div>
                                    </div>
                                    <input type="hidden" id="cover_image" name="image_url" />
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
	                    <div class="col-sm-8">
	                         <div class="form-group ">
	                            <label>文章简介</label>
	                            <textarea name="description"  class="form-control" rows="5">{{ old('description') }}</textarea>
	                         </div>
	                    </div>
                    </div>

                    <div class="row">
	                    <div class="col-sm-8">
	                            <div class="input-group form-group">
	                                <span class="input-group-addon"><i class="zmdi zmdi-calendar">发送日期</i></span>
	                                <div class="dtp-container fg-line">
	                                    <input type="text" style="width:20%" class="form-control date-picker" placeholder="发送日期"
	                                           name='ymd' value="{{ old('ymd') }}">
	                                <select name="hours" id="hours">
						          			<option value="" selected>小时</option>
						          			<option value="01" >01</option>
						          			<option value="02" >02</option>
						          			<option value="03" >03</option>
						          			<option value="04" >04</option>
						          			<option value="05" >05</option>
						          			<option value="06" >06</option>
						          			<option value="07" >07</option>
						          			<option value="08" >08</option>
						          			<option value="09" >09</option>
						          			<option value="10" >10</option>
						          			<option value="11" >11</option>
						          			<option value="12" >12</option>
						          			<option value="13" >13</option>
						          			<option value="14" >14</option>
						          			<option value="15" >15</option>
						          			<option value="16" >16</option>
						          			<option value="17" >17</option>
						          			<option value="18" >18</option>
						          			<option value="19" >19</option>
						          			<option value="20" >20</option>
						          			<option value="21" >21</option>
						          			<option value="22" >22</option>
						          			<option value="23" >23</option>
						          			<option value="00" >00</option>
						          		</select>	
	                                
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
    <script type="text/javascript" src="/vendors/webuploader/webuploader.min.js"></script>
    <script type="text/javascript" src="/vendors/webuploader/carousel_webupload.js"></script>

    <script type="text/javascript">
		$(function(){
			$('#hours').val("{{ old('hours') }}")
		})
	
    
        /** 类型选择 */
        $("#selectId").change(function(){
            var selectId = $(this).val();
            showDiv(selectId);
        });
        showDiv($("#selectId").val());
        function showDiv(selectId){
            $('.r_item').hide();
            if(selectId==2){
                $('.r_url').show();
            }else if(selectId==3) {
                $('.r_cnt').show();
            }
        }
    </script>
@endsection