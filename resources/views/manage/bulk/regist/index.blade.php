@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    一括登録
                </div>
                <div class="card-body">
                    @include('layouts.parts.message')

                    <h3>CSVアップロード</h3>
                    {{ Form::open(['route'=>['manage.bulk.regist.csv'], 'method'=>'POST', 'class'=>'form-inline']) }}
                        <div class="form-group">
                        {{ Form::file('csv_file', ['class'=>''.ViewUtil::hasErrorClass($errors, 'csv_file')]) }}
                        </div>
                        {{ Form::submit('送信', ['class'=>'form-control']) }}
                        @include('layouts.parts.error_message', ['key'=>'csv_file'])
                    {{ Form::close() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
