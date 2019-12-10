@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    アルバム詳細
                </div>
                <div class="card-body">
                    <h2>{{ $album->title }}</h2>

                    <div class="form-group row">
                        {{ Form::label('album_artist', 'アルバムアーティスト', ['class'=>'col-form-label col-md-4'])}}
                        <div class="col-md-8">
                            <p class="form-control-plaintext">{{ Html::linkRoute('search.album_artist.show', $album->artist_name, [$album->artist->id]) }}</p>
                        </div>
                    </div>

                    <div class="form-group row">
                        {{ Form::label('album', 'アルバム楽曲', ['class'=>'col-form-label col-md-4'])}}
                        <div class="col-md-8">
                            <table class="table table-borderd table-striped">
                                <thead>
                                    <tr>
                                        <th>Track No</th>
                                        <th>曲名</th>
                                    </tr>
                                </thead>
                                <tbody>
                                @foreach($music->album->musics->sortBy('track_no') as $music)
                                    <tr>
                                        <td>{{ $music->track_no }}</td>
                                        <td>{{ Html::linkRoute('search.music.show', $music->title, [$music->id]) }}</td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="form-group row">
                        {{ Form::label('description', '詳細・アルバムに携わった人等', ['class'=>'col-form-label col-md-4'])}}
                        <div class="col-md-8">
                            {{ Form::textarea('description', $music->album->description, ['class'=>'form-control-plaintext', 'rows'=>'6', 'readonly']) }}
                        </div>
                    </div>

                    <div class="form-group row">
                        {{ Form::label('music', '楽曲詳細', ['class'=>'col-form-label col-md-4'])}}
                        <div class="col-md-8">
                            <table class="table table-borderd table-striped">
                                <thead>
                                    <tr>
                                        <th>Track No</th>
                                        <th>楽曲名</th>
                                        <th>アーティスト名</th>
                                        <th>パート</th>
                                    </tr>
                                </thead>
                                <tbody>
                                @foreach($album->musics as $music)
                                    @foreach($music->parts as $part)
                                        <tr>
                                                <td>{{ $music->track_no }}</td>
                                            <td>{{ Html::linkRoute('search.music.show', $music->title, [$music->id]) }}</td>
                                            <td>{{ Html::linkRoute('search.artist.show', $part->artist_name, [$part->artist->id]) }}</td>
                                            <td>{{ $part->part_name }}</td>
                                        </tr>
                                    @endforeach
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
