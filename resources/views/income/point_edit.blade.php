@extends('layouts.material')
@section('content')
    <div class="container">
        <div class="block-header">
            <h2><a href="{{route('income.point')}}">积分管理</a> -> 编辑</h2>
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
                <form id="post_form" action="{{route('income.point_update',['id'=>$user->id])}}" method="post">
                    {!! csrf_field() !!}
                    <div class="row">
                        <div class="col-sm-2">
                            <div class="form-group fg-line ">
                                <label>手机号</label>
                                <div class="fg-line form-group">
                                    {{$user->mobile}}
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-2">
                            <div class="form-group fg-line ">
                                <label>昵称</label>
                                <div class="fg-line form-group">
                                    {{$user->nickname}}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-2">
                            <div class="form-group fg-line ">
                                <label>角色</label>
                                <div class="fg-line form-group">
                                    {{ $user_role[$user->role]}}
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-2">
                            <div class="form-group fg-line ">
                                <label>当前积分值</label>
                                <div class="fg-line form-group">
                                    {{$user->score}}
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-2">
                            <div class="form-group fg-line ">
                                <label>增加值</label>
                                <input class="form-control input-sm" name="point_value" type="text" placeholder="10" value="{{old('point_value')}}">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group fg-line ">
                                <label>理由</label>
                                <input class="form-control input-sm" name="remark" type="text" placeholder="请填写增加积分的理由" value="{{old('remark')}}">
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


