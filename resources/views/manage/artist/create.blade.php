@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                アーティスト登録
            </div>
            <div class="card-body">
                {{ Form::open(['route'=>['manage.artist.store'], 'method'=>'POST', 'class'=>'']) }}
                <div class="form-group row">
                    {{ Form::label('name', 'アーティスト名', ['class'=>'col-form-label col-md-4'])}}
                    <div class="col-md-8">
                        {{ Form::text('name', null, ['class'=>'form-control']) }}
                    </div>
                </div>

                <div class="form-group row">
                    {{ Form::label('belonging', '所属事務所', ['class'=>'col-form-label col-md-4'])}}
                    <div class="col-md-8">
                        {{ Form::textarea('belonging', null, ['class'=>'form-control', 'cols'=>'6']) }}
                        <p class="help-block">※複数ある場合、もしくは変更する場合は改行で追記してください。</p>
                    </div>
                </div>

                <div class="form-group row">
                    {{ Form::submit('登録', ['class'=>'btn btn-primary']) }}
                </div>

                {{ Form::close() }}
            </div>
        </div>
    </div>
</div>
@endsection
