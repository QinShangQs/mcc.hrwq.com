@extends('layouts.material')
@section('content')
    <div class="container">
        <div class="block-header">
            <h2><a href="{{route('vcourse.marks')}}">笔记</a>  -> 笔记详情</h2>
        </div>
        <div class="card">
            <div class="card-body card-padding">
                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group fg-line ">
                            <label>课程名称</label>
                            <input type="text" value="{{$vcourseMark->vcourse->title}}" name="title"  class="form-control input-sm" disabled="disabled">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-3">
                        <div class="form-group fg-line ">
                            <label>作业用户</label>
                            <input type="text" value="{{$vcourseMark->user->nickname}}" name="nickname"  class="form-control input-sm" disabled="disabled">
                        </div>
                    </div>
                </div>
                 <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group fg-line ">
                            <label>笔记内容</label>
                            <textarea class="form-control" rows="5" placeholder="" name="mark_content" disabled="disabled">{{$vcourseMark->mark_content}}</textarea>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-2">
                        <div class="form-group fg-line ">
                            <label>提交日期</label>
                            <input type="text" class="form-control date-time-picker" placeholder="" name='created_at' value="{{ $vcourseMark->created_at }}" disabled="disabled">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-3">
                        <div class="form-group fg-line ">
                            <label>是否公开</label>
                            <select class="selectpicker" size="10"  name="visible" disabled="disabled">
                                @foreach ($visible_list as $key=>$item)
                                    <option value="{{$key}}" @if($vcourseMark->visible==$key) selected @endif>{{$item}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script type="text/javascript">
        $(function(){
            if ($("input[name='type']:checked").val()==1) {
               $('#price').hide();
            } else if ($("input[name='type']:checked").val()==2) {
               $('#price').show();
            }
            //收费类别
            $("input[name='type']").change(function(){
               if ($(this).val()==1) {
                  $('#price').hide();
               } else {
                  $('#price').show();
               }
            });
        })
    </script>
@endsection
