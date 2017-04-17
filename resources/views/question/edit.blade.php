@extends('layouts.material')
@section('content')
    <div class="container">
        <div class="block-header">
            <h2><a href="{{route('question.index')}}">问题榜</a> -> 问题编辑</h2>
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
                    <form id="post_form" action="{{route('question.update',['id'=>$question->id])}}" method="post">
                        {!! csrf_field() !!}
                        <div class="row">
                            <div class="col-sm-9">
                                <div class="form-group fg-line ">
                                    <label>问题描述</label>
                                    <input type="text" value="{{$question->content}}"   class="form-control input-sm"  disabled="disabled">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-2">
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
                            <div class="col-sm-1">
                                <div class="form-group fg-line">
                                    <label>收听次数</label>
                                    <input type="text" value="{{$question->listener_nums}}"  name="listener_nums"   class="form-control input-sm"  >
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-2">
                                <div class="form-group fg-line ">
                                    <label>限时免费(<small class="c-red">开始，截止时间为空表示不免费</small>)</label>
                                    <input type="text" class="form-control date-time-picker input-sm" name='free_from' placeholder="开始时间" value="{{ $question->free_from }}">
                                    <input type="text" class="form-control date-time-picker input-sm" name='free_end' placeholder="截止时间"  value="{{$question->free_end }}">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-2">
                                <div class="form-group fg-line ">
                                    <label>排序值<small class="c-red">(数字越大越靠前)</small></label>
                                    <input type="text" value="{{old('sort')}}" name="sort" placeholder="" class="form-control input-sm" >
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
