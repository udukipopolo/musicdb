@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    アーティスト管理
                </div>
                <div class="panel-body">
                    {{ Form::open(['route'=>['manage.artist.index'], 'method'=>'GET', 'class'=>'form-inline']) }}
                        <div class="form-group">
                            <label class="sr-only" form="name">アーティスト名</label>
                            {{ Form::text('name', null, ['class'=>'form-control', 'placeholder'=>'アーティスト名']) }}
                        </div>
                        {{ Form::submit('検索', ['class'=>'btn btn-default']) }}
                        {{ Html::linkRoute('manage.artist.create', '新規登録', [], ['class'=>'btn btn-primary']) }}
                    {{ Form::close() }}
                </div>
            </div>
        </div>

        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    アーティスト一覧
                </div>
                <div class="panel-body">
                </div>
            </div>
        </div>

    </div>
</div>
@endsection
