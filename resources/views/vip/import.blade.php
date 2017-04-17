@extends('layouts.material')
@section('style')
    <link href="/vendors/webuploader/webuploader.css" rel="stylesheet">
@endsection
@section('content')
    <div class="container">
        <div class="block-header">
            <h2><a href="{{route('vip.index')}}">激活码</a>  -> 激活码上传</h2>
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

                <form id="post_form" action="{{route('vip.do_import')}}" method="post"  enctype="multipart/form-data">
                    {!! csrf_field() !!}
                    <div class="row form-group fg-line">
                       <button class="download btn bgm-red" >下载上传模板</button>
                    </div>
                    <div class="row form-group fg-line">
                            <div class="form-group fg-line ">
                                <label for="exampleInputEmail1">文件Excel</label>
                                <input type="file" id="file" name="file" />
                            </div>
                    </div>


                    <div class="form-group fg-line">
                        <button class="btn bgm-cyan waves-effect" >导入</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script type="text/javascript">
        $('.download').click(function(e){
            e.preventDefault();
            location.href = '{{asset('uploads/vip_code_demo.xlsx')}}';
        })
    </script>
@endsection