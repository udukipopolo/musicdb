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

@if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

                <div class="form-group row">
                    {{ Form::label('name', 'アーティスト名', ['class'=>'col-form-label col-md-4'.ViewUtil::hasErrorClass($errors, 'name')])}}
                    <div class="col-md-8">
                        {{ Form::text('name', null, ['class'=>'form-control']) }}
                        @include('layouts.parts.error_message', ['key'=>'name'])
                    </div>
                </div>

                <div class="form-group row">
                    {{ Form::label('belonging', '所属事務所', ['class'=>'col-form-label col-md-4'])}}
                    <div class="col-md-8">
                        {{ Form::textarea('belonging', null, ['class'=>'form-control'.ViewUtil::hasErrorClass($errors, 'belonging'), 'cols'=>'6']) }}
                        <p class="help-block">※複数ある場合、もしくは変更する場合は改行で追記してください。</p>
                        @include('layouts.parts.error_message', ['key'=>'belonging'])
                    </div>
                </div>

                <div class="form-group row">
                    <div class="col-md-12">
                        {{ Form::submit('登録', ['class'=>'btn btn-primary']) }}
                    </div>
                </div>

                {{ Form::close() }}
            </div>
        </div>
    </div>
</div>
@endsection
