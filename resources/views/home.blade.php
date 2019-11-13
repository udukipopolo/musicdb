@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">楽曲検索</div>

                <div class="card-body">
                    {{ Form::open(['route'=>['search.music.index'], 'method'=>'GET', 'class'=>'']) }}
                        <div class="row">
                            <div class="col-md-4">
                                <label class="sr-only" form="name">アルバムタイトル</label>
                                {{ Form::text('album_title', @$input['album_title'], ['class'=>'form-control', 'placeholder'=>'アルバムタイトル']) }}
                            </div>
                            <div class="col-md-4">
                                <label class="sr-only" form="music_title">楽曲名</label>
                                {{ Form::text('music_title', @$input['music_title'], ['class'=>'form-control', 'placeholder'=>'楽曲名']) }}
                            </div>
                            <div class="col-md-4">
                                <label class="sr-only" form="artist_name">アーティスト名</label>
                                {{ Form::text('artist_name', @$input['artist_name'], ['class'=>'form-control', 'placeholder'=>'アーティスト名']) }}
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                {{ Form::submit('検索', ['class'=>'btn btn-light']) }}
                            </div>
                        </div>
                    {{ Form::close() }}
                </div>
            </div>
        </div>

        <div class="col-md-8">
            <div class="card">
                <div class="card-header">アーティスト検索</div>

                <div class="card-body">
                    {{ Form::open(['route'=>['search.artist.index'], 'method'=>'GET', 'class'=>'']) }}
                        <div class="row">
                            <div class="col-md-4">
                                <label class="sr-only" form="artist_name">アーティスト名</label>
                                {{ Form::text('artist_name', @$input['artist_name'], ['class'=>'form-control', 'placeholder'=>'アーティスト名']) }}
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                {{ Form::submit('検索', ['class'=>'btn btn-light']) }}
                            </div>
                        </div>
                    {{ Form::close() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
