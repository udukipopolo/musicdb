@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    アーティスト詳細
                    @auth
                    <div class="btn-toolbar float-right" role="toolbar">
                        <div class="btn-group" role="group">
                            <a href="{{ route('manage.artist.edit', [$artist->id]) }}" class="btn btn-success">編集</a>
                        </div>
                    </div>
                    @endauth
                </div>
                <div class="card-body">
                    <h2>{{ $artist->name }}</h2>

                    <h3>所属事務所</h3>

                    <div class="col-md-12">
                        {{ Form::textarea('belonging', $artist->belonging, ['class'=>'form-control-plaintext', 'rows'=>'4', 'readonly']) }}
                    </div>


                    <h3>担当楽曲一覧</h3>

                    <div class="col-md-12">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>アルバム</th>
                                    <th>アルバムアーティスト</th>
                                    <th>曲名</th>
                                    <th>名義</th>
                                    <th>パート</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($parts as $part)
                                <tr>
                                    <td>{{ Html::linkRoute('search.album.show', $part->music->album->title, [$part->music->album->id]) }}</td>
                                    <td>{{ Html::linkRoute('search.album_artist.show', $part->music->album->artist_name, [$part->music->album->artist_id]) }}</td>
                                    <td>{{ Html::linkRoute('search.music.show', $part->music->title, [$part->music->id]) }}</td>
                                    <td>{{ $part->artist_name }}</td>
                                    <td>{{ $part->part_name }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    {{ $parts->appends(Request::input())->links() }}

                    <h3>アルバム一覧</h3>

                    <div class="row">
                        @foreach($artist->albums as $album)
                        <div class="col-lg-3 col-md-4 col-sm-6">
                            <div class="card">
                                <div class="card-body">
                                {{ Html::linkRoute('search.album.show', $album->title, [$album->id]) }}
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>

                    <div class="row">
                        <div class="col-12 text-right">
                            <p>{{ Html::linkRoute('search.album_artist.show', '楽曲一覧を見る', [$artist->id]) }}</p>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
