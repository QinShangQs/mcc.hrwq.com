@extends('layouts.material')
@section('content')
    <div class="container">
        <div class="block-header">
            <h2><a href="{{route('question.tags')}}">标签列表</a>  -> 添加标签</h2>
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

                <form id="post_form" action="{{route('question.tag_store')}}" method="post">
                    {!! csrf_field() !!}
                    <div class="row">
                        <div class="col-sm-2">
                            <div class="form-group fg-line ">
                                <label>类别名</label>
                                <input type="text" value="{{old('title')}}" name="title"  class="form-control input-sm" >
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-2">
                            <div class="form-group fg-line ">
                                <label>排序 <small class="c-red">(数字越大越靠前)</small></label>
                                <input type="text" value="{{old('sort')}}" name="sort"  class="form-control input-sm" >
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
