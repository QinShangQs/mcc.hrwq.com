@extends('layouts.material')
@section('content')
    <div class="container">
        <div class="block-header">
            <h2>账号列表</h2>
        </div>
        <div class="card">
            <div class="card-body table-responsive">
                <table id="data-table-selection" class="table table-striped">
                    <thead>
                    <tr>
                        <th>ID</th>
                        <th>账号名</th>
                        <th>账号类型</th>
                    </tr>
                    </thead>
                    <tbody>
                    @if(count($accounts))
                        @foreach($accounts as $item)
                            <tr>
                                <td>{{$item->id}}</td>
                                <td>{{ $item->name }}</td>
                                <td>{{\App\Models\Admin::getAdminTypeStr($item->admin_type)}}</td>
                            </tr>
                        @endforeach
                    @endif
                    </tbody>
                </table>
                {!! $accounts->render() !!}
            </div>
        </div>
    </div>
@endsection