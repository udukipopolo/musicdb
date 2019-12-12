@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    アルバムアーティスト詳細
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


                    <h3>担当アルバム曲一覧</h3>

                    <div class="col-md-12">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>アルバム</th>
                                    <th>名義</th>
                                    <th>Track No</th>
                                    <th>曲名</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($musics as $music)
                                <tr>
                                    <td>{{ Html::linkRoute('search.album.show', $music->album->title, [$music->album->id]) }}</td>
                                    <td>{{ $music->album->artist_name }}</td>
                                    <td>{{ $music->track_no }}</td>
                                    <td>{{ Html::linkRoute('search.music.show', $music->title, [$music->id]) }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    {{ $musics->appends(Request::input())->links() }}

                    <div class="col-12 text-right">
                        <p>{{ Html::linkRoute('search.artist.show', 'アーティスト担当楽曲を見る', [$artist->id]) }}</p>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
