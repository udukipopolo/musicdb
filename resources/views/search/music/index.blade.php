@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    楽曲検索
                </div>
                <div class="card-body">
                    @include('layouts.parts.message')

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

        @isset($musics)
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    検索結果
                </div>
                <div class="card-body">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>アルバム名</th>
                                <th>アルバムアーティスト</th>
                                <th>楽曲名</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($musics as $music)
                            <tr>
                                <td>{{ $music->album->title }}</td>
                                <td>{{ $music->album->artist_name }}</td>
                                <td>{{ Html::linkRoute('search.music.show', $music->title, [$music->id]) }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>

                    {{ $musics->appends(Request::input())->links() }}
                </div>
            </div>
        </div>
        @endisset
    </div>
</div>
@endsection