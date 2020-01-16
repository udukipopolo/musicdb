@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12 mb-3">
            <div class="card">
                <div class="card-header">@lang('messages.search_music')</div>

                <div class="card-body">
                    {{ Form::open(['route'=>['search.music.index'], 'method'=>'GET', 'class'=>'']) }}
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label class="sr-only" form="name">@lang('messages.album_title')</label>
                                {{ Form::text('album_title', @$input['album_title'], ['class'=>'form-control', 'placeholder'=>__('messages.album_title')]) }}
                            </div>
                            <div class="col-md-4">
                                <label class="sr-only" form="music_title">@lang('messages.music_title')</label>
                                {{ Form::text('music_title', @$input['music_title'], ['class'=>'form-control', 'placeholder'=>__('messages.music_title')]) }}
                            </div>
                            <div class="col-md-4">
                                <label class="sr-only" form="artist_name">@lang('messages.artist_id')</label>
                                {{ Form::text('artist_name', @$input['artist_name'], ['class'=>'form-control', 'placeholder'=>__('messages.artist_id')]) }}
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                {{ Form::submit(__('messages.search'), ['class'=>'btn btn-light']) }}
                            </div>
                        </div>
                    {{ Form::close() }}
                </div>
            </div>
        </div>

        <div class="col-md-12 mb-3">
            <div class="card">
                <div class="card-header">@lang('messages.search_artist')</div>

                <div class="card-body">
                    {{ Form::open(['route'=>['search.artist.index'], 'method'=>'GET', 'class'=>'']) }}
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label class="sr-only" form="artist_name">@lang('messages.artist_id')</label>
                                {{ Form::text('artist_name', @$input['artist_name'], ['class'=>'form-control', 'placeholder'=>__('messages.artist_id')]) }}
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                {{ Form::submit(__('messages.search'), ['class'=>'btn btn-light']) }}
                            </div>
                        </div>
                    {{ Form::close() }}
                </div>
            </div>
        </div>

        @if(config('app.debug'))
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <p>※注意事項</p>
                    <ul>
                        <li>現在α版としての公開となります。予告なく変更・データの削除等が行われる場合がございます。</li>
                        <li>本番運用に際しては、ドメイン（URL）が変わる予定です。</li>
                        <li>現段階ではサロン外への公開は禁止とさせていただきます。</li>
                    </ul>
                </div>
            </div>
        </div>
        @endif

    </div>
</div>
@endsection
