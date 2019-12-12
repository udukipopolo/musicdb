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
                    {{ Form::label('artist_id', 'アーティスト名', ['class'=>'col-form-label col-md-4'])}}
                    <div class="col-md-8">
                        {{ Form::text('artist_id', null, ['class'=>'form-control'.ViewUtil::hasErrorClass($errors, 'artist_id'), 'placeholder'=>'Q-MHz']) }}
                        @include('layouts.parts.error_message', ['key'=>'artist_id'])
                    </div>
                </div>

                <div class="form-group row">
                    {{ Form::label('belonging', '所属事務所', ['class'=>'col-form-label col-md-4'])}}
                    <div class="col-md-8">
                        {{ Form::textarea('belonging', null, ['class'=>'form-control'.ViewUtil::hasErrorClass($errors, 'belonging'), 'rows'=>'6']) }}
                        @include('layouts.parts.error_message', ['key'=>'belonging'])
                        <p class="help-block">※複数ある場合、もしくは変更する場合は改行で追記してください。</p>
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
