@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    楽曲詳細
                    @auth
                    <div class="btn-toolbar float-right" role="toolbar">
                        <div class="btn-group" role="group">
                            <a href="{{ route('manage.album.edit', [$album->id]) }}" class="btn btn-success">編集</a>
                        </div>
                    </div>
                    @endauth
                </div>
                <div class="card-body">
                    <h2>{{ $music->title }}</h2>

                    <div class="form-group row">
                        {{ Form::label('album_title', 'アルバムタイトル', ['class'=>'col-form-label col-md-4'])}}
                        <div class="col-md-8">
                            <p class="form-control-plaintext">{{ Html::linkRoute('search.album.show', $music->album->title, [$music->album->id]) }}</p>
                        </div>
                    </div>

                    <div class="form-group row">
                        {{ Form::label('album_title', 'アルバムアーティスト', ['class'=>'col-form-label col-md-4'])}}
                        <div class="col-md-8">
                            <p class="form-control-plaintext">{{ Html::linkRoute('search.album_artist.show', $music->album->artist_name, [$music->album->artist->id]) }}</p>
                        </div>
                    </div>

                    <div class="form-group row">
                        {{ Form::label('part', 'パート', ['class'=>'col-form-label col-md-4'])}}
                        <div class="col-md-8">
                            <table class="table table-borderd table-striped">
                                <thead>
                                    <tr>
                                        <th>アーティスト名</th>
                                        <th>パート</th>
                                    </tr>
                                </thead>
                                <tbody>
                                @foreach($music->parts as $part)
                                    <tr>
                                        <td>{{ Html::linkRoute('search.artist.show', $part->artist_name, [$part->artist->id]) }}</td>
                                        <td>{{ $part->part_name }}</td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
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

                    <h4>商品LINK</h4>
                    <div class="row">
                        <div class="col-md-12">
                            @if ($music->album->affi_apple_music)
                            {{ Html::link($music->album->affi_apple_music, 'Apple Music', ['class'=>'btn btn-light', 'target'=>'_blank']) }}
                            @endif
                            @if ($music->album->affi_amazon)
                            {{ Html::link($music->album->affi_amazon, 'Amazon', ['class'=>'btn btn-light', 'target'=>'_blank']) }}
                            @endif
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
