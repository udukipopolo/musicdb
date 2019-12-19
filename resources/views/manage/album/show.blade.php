@extends('layouts.app')

@section('head')
@endsection

@section('content')
<div class="container-fluid">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                アルバム詳細
                <div class="btn-toolbar float-right" role="toolbar">
                    <div class="btn-group" role="group">
                        <a href="{{ route('manage.album.edit', [$album->id]) }}" class="btn btn-success">編集</a>
                    </div>
                    <div class="btn-group" role="group">
                        {{ Form::open(['route'=>['manage.album.destroy', $album->id], 'method'=>'DELETE', 'onsubmit'=>'return confirm("アルバムを削除しますか？");']) }}
                            {{ Form::submit('削除', ['class'=>'btn btn-danger']) }}
                        {{ Form::close() }}
                    </div>
                </div>
                <div class="clearfix"></div>
            </div>
            <div class="card-body">
                @include('layouts.parts.message')

                <div class="form-group row">
                    {{ Form::label('title', 'アルバムタイトル', ['class'=>'col-form-label col-md-4'])}}
                    <div class="col-md-8">
                        {{ Form::text('title', $album->getLocaleName('title', 'ja'), ['class'=>'form-control-plaintext', 'readonly']) }}
                    </div>
                </div>

                <div class="form-group row">
                    {{ Form::label('artist', 'アーティスト', ['class'=>'col-form-label col-md-4'])}}
                    <div class="col-md-8">
                        {{ Form::text('artist_name', $album->getLocaleName('artist_name', 'ja'), ['class'=>'form-control-plaintext', 'readonly']) }}
                    </div>
                </div>

                <div class="form-group row">
                    {{ Form::label('musics', '楽曲', ['class'=>'col-form-label col-md-4'])}}
                    <div class="col-md-8">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>曲名</th>
                                </tr>
                            </thead>
                            <tbody id="track_list">
                                @foreach($album->musics->sortBy('track_no') as $music)
                                <tr>
                                    <td>{{ $music->track_no }}</td>
                                    <td>{{ Html::linkRoute('manage.album.music.edit', $music->getLocaleName('title', 'ja'), [$album->id, $music->id], []) }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="form-group row">
                    {{ Form::label('description', '詳細・アルバムに携わった人等', ['class'=>'col-form-label col-md-4'])}}
                    <div class="col-md-8">
                        {{ Form::textarea('description', $album->getLocaleText('description', 'ja'), ['class'=>'form-control-plaintext', 'rows'=>'6', 'readonly']) }}
                    </div>
                </div>

                <h4>アフィリエイト</h4>

                <div class="form-group row">
                    {{ Form::label('affi_apple_music', 'Apple Music URL', ['class'=>'col-form-label col-md-4'])}}
                    <div class="col-md-8">
                        {{ Form::text('affi_apple_music', $album->affi_apple_music, ['class'=>'form-control-plaintext', 'readonly']) }}
                    </div>
                </div>

                <div class="form-group row">
                    {{ Form::label('affi_amazon', 'Amazon URL', ['class'=>'col-form-label col-md-4'])}}
                    <div class="col-md-8">
                        {{ Form::text('affi_amazon', $album->affi_amazon, ['class'=>'form-control-plaintext', 'readonly']) }}
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
@endsection
