@extends('layouts.app')

@section('head')
{{ Html::style('css/select2.min.css') }}
{{ Html::script('js/select2.min.js') }}
<script>
$(document).ready(function(){
    $('.js-select2').select2();
});
</script>

@endsection

@section('content')
<div class="container-fluid">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                アルバム登録
            </div>
            <div class="card-body">
                {{ Form::open(['route'=>['manage.album.store'], 'method'=>'POST', 'class'=>'']) }}

                <div class="form-group row">
                    {{ Form::label('title', 'アルバムタイトル', ['class'=>'col-form-label col-md-4'])}}
                    <div class="col-md-8">
                        {{ Form::text('title', null, ['class'=>'form-control'.ViewUtil::hasErrorClass($errors, 'title')]) }}
                        @include('layouts.parts.error_message', ['key'=>'title'])
                    </div>
                </div>

                <div class="form-group row">
                    {{ Form::label('artist', 'アーティスト', ['class'=>'col-form-label col-md-4'])}}
                    <div class="col-md-4">
                        {{ Form::select('artist_id', [''=>'新規登録']+$artists->toArray(), null, ['class'=>'form-control js-select2'.ViewUtil::hasErrorClass($errors, 'artist_id'), 'id'=>'album_artist_id']) }}
                        @include('layouts.parts.error_message', ['key'=>'artist_id'])
                    </div>
                    <div class="col-md-4">
                        {{ Form::text('artist_name', null, ['class'=>'form-control'.ViewUtil::hasErrorClass($errors, 'artist_name'), 'id'=>'album_artist_name']) }}
                        @include('layouts.parts.error_message', ['key'=>'artist_name'])
                    </div>
                </div>

                <div class="form-group row">
                    {{ Form::label('musics', '楽曲', ['class'=>'col-form-label col-md-4'])}}
                    <div class="col-md-8">
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
