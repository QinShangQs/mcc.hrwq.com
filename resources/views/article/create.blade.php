@extends('layouts.material')
@section('content')
    <div class="container">
        <div class="block-header">
            <h2><a href="{{route('article')}}">文案列表</a>  -> 添加文案</h2>
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
                <form id="post_form" action="{{route('article.store')}}" method="post">
                    {!! csrf_field() !!}
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group fg-line ">
                                <label>标题</label>
                                <input type="text" value="{{old('title')}}"  name="title"    class="form-control input-sm" >
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-2">
                            <div class="form-group fg-line ">
                                <label>类型</label>
                                <select id="selectId" class="selectpicker" name="type">
                                    <option value=""  >--请选择类型--</option>
                                    @foreach($type as $k=>$v)
                                        <option value="{{ $k }}" @if($k==old('type')) selected @endif>{{$v}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-12">
                            <div class="form-group fg-line ">
                                <label>内容</label>
                                <textarea  id="ueditor" name="content" >{{old('content')}}</textarea>
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
    <script type="text/javascript" charset="utf-8" src="/vendors/Ueditor/ueditor.config.js"></script>
    <script type="text/javascript" charset="utf-8" src="/vendors/Ueditor/ueditor.all.min.js"> </script>
    <script type="text/javascript" charset="utf-8" src="/vendors/Ueditor/lang/zh-cn/zh-cn.js"></script>
    <script type="text/javascript">
        $(function(){
            UE.getEditor('ueditor',{initialFrameHeight:300});
        })
    </script>
@endsection