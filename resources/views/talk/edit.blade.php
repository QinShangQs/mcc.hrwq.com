@extends('layouts.material')
@section('content')
    <div class="container">
        <div class="block-header">
            <h2><a href="{{route('talk.index')}}">互助榜</a> -> 问题编辑</h2>
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
                    <form id="post_form" action="{{route('talk.update',['id'=>$question->id])}}" method="post">
                        {!! csrf_field() !!}
                        <div class="row">
                            <div class="col-sm-9">
                                <div class="form-group fg-line ">
                                    <label>问题标题</label>
                                    <input type="text" value="{{$question->title}}"   class="form-control input-sm"  disabled="disabled">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-3">
                                <div class="form-group fg-line ">
                                    <label>标签(<small class="c-red">最多选三个</small>)</label>
                                    <select class="selectpicker"  multiple="multiple" size="10"  name="tags[]">
                                        @foreach ($all_tags as $item)
                                            <option value="{{$item->id}}" @if(in_array($item->id,$cur_tags)) selected @endif>{{$item->title}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-3">
                                <div class="form-group   fg-line ">
                                    <label>查看次数</label>
                                    <input type="text" value="{{$question->view}}"  name="view"   class="form-control input-sm"  >
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-3">
                                <div class="form-group fg-line ">
                                    <label>排序 <small class="c-red">(数字越大越靠前)</small></label>
                                    <input type="text" value="{{$question->sort}}"  name="sort"   class="form-control input-sm">
                                </div>
                            </div>
                        </div>


                        <div class="form-group fg-line">
                            <button class="btn bgm-cyan waves-effect">保存</button>
                        </div>
                    </form>
            </div>
        </div>
    </div>
@endsection
