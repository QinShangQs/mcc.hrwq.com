@extends('layouts.material')
@section('content')
    <div class="container">
        <div class="block-header">
            <h2>标签列表</h2>
        </div>
        <div class="card">
            <div class="card-header card-padding">
                <a href="{{route('income.scale_add')}}">
                    <button class="btn btn-success btn-sm  waves-effect ">
                        <i class="zmdi zmdi-plus zmdi-hc-fw"></i>添加收益比例
                    </button>
                </a>
            </div>

            <div class="card-body table-responsive">
                <table id="data-table-selection" class="table table-striped">
                    <thead>
                    <tr>
                        <th>ID</th>
                        <th>收益类型</th>
                        <th>平台比例</th>
                        <th>指导师比例</th>
                        <th>提问人比例</th>
                        <th>操作</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach ($data as $item)
                        <?php $tmp_arr = unserialize($item->value); ?>
                        <tr>
                            <td>{{ $item->id }}</td>
                            <td>{{ $income_scale_keys[$item->key]}}</td>
                            <td>{{ $tmp_arr['p_scale'] }}</td>
                            <td>{{ $tmp_arr['t_scale'] }}</td>
                            <td>{{ $tmp_arr['a_scale'] }}</td>
                            <td>
                                <a href="{{route('income.scale_edit',['id'=>$item->id])}}">
                                    <button class="btn bgm-orange waves-effect"><i class="zmdi zmdi-edit"></i></button>
                                </a>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection