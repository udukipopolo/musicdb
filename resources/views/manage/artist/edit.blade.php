@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                アーティスト編集
            </div>
            <div class="card-body">
                {{ Form::open(['route'=>['manage.artist.update', $artist->id], 'method'=>'PUT', 'class'=>'']) }}

                <div class="form-group row">
                    {{ Form::label('name', 'アーティスト名', ['class'=>'col-form-label col-md-4'])}}
                    <div class="col-md-8">
                        {{ Form::text('name', $artist->name, ['class'=>'form-control'.ViewUtil::hasErrorClass($errors, 'name')]) }}
                        @include('layouts.parts.error_message', ['key'=>'name'])
                    </div>
                </div>

                <div class="form-group row">
                    {{ Form::label('belonging', '所属事務所', ['class'=>'col-form-label col-md-4'])}}
                    <div class="col-md-8">
                        {{ Form::textarea('belonging', $artist->belonging, ['class'=>'form-control'.ViewUtil::hasErrorClass($errors, 'belonging'), 'cols'=>'6']) }}
                        <p class="help-block">※複数ある場合、もしくは変更する場合は改行で追記してください。</p>
                        @include('layouts.parts.error_message', ['key'=>'belonging'])
                    </div>
                </div>

                <div class="form-group row">
                    <div class="col-md-12">
                        {{ Form::submit('更新', ['class'=>'btn btn-primary']) }}
                    </div>
                </div>

                {{ Form::close() }}
            </div>
        </div>
    </div>
</div>
@endsection
