@extends('layouts.material')

@section('content')
    <div class="container">
        <div class="block-header">
            <h2><a href="{{route('vip.index')}}">和会员价格维护</a> -> 和会员价格编辑</h2>
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

                <form id="post_form"  action="{{route('vip.price_update',['id'=>$config_price->id])}}" method="post">
                    {!! csrf_field() !!}
                    <input type="hidden" name="id" id="id" value="{{$config_price->id}}">

                    <div class="row">
                        <div class="col-sm-3">
                            <div class="form-group fg-line ">
                                <label for="exampleInputEmail1">和会员价格</label>
                                <input type="text" value="{{ $config_price->vip_price }}" name="vip_price"  class="form-control input-sm">
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
