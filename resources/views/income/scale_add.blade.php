@extends('layouts.material')
@section('content')
    <div class="container">
        <div class="block-header">
            <h2><a href="{{route('income.scale')}}">收益比例管理</a> -> 添加</h2>
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
                <form id="post_form" action="{{route('income.scale_store')}}" method="post">
                    {!! csrf_field() !!}
                    <div class="row">
                        <div class="col-sm-2">
                            <div class="form-group fg-line ">
                                <label>收益类型</label>
                                <select id="selectId" class="selectpicker" name="key">
                                    <option value=""  >--请选择类型--</option>
                                    @foreach($income_scale_keys as $k=>$v)
                                        <option value="{{ $k }}" @if($k==old('key')) selected @endif>{{$v}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-xs-3">
                            <div class="fg-line form-group">
                                比例值 <small class="c-red">(百分比,各项之和要为100,为0表示不参与分成)</small>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                            <div class="col-xs-3">
                                <label>平台</label>
                                <div class="fg-line form-group">
                                    <input class="form-control input-sm" name="p_scale" type="text" placeholder="50" value="{{old('p_scale')}}">
                                </div>
                            </div>
                            <div class="col-xs-3">
                                <label>指导师</label>
                                <div class="fg-line form-group">
                                    <input class="form-control input-sm"  name="t_scale" type="text" placeholder="30" value="{{old('t_scale')}}">
                                </div>
                            </div>
                            <div class="col-xs-3">
                                <label>提问人</label>
                                <div class="fg-line form-group">
                                    <input class="form-control input-sm"  name="a_scale" type="text" placeholder="20" value="{{old('a_scale')}}">
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


