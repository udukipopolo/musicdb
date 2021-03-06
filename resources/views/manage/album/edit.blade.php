@extends('layouts.app')

@section('head')
{{ Html::style('css/select2.min.css') }}
{{ Html::script('js/select2.min.js') }}
<script>
$(document).ready(function(){
    $('#add_row').click(function() {
        var track_no = parseInt($('#max_num').val()) + 1;

        var row = '<tr><td>'+track_no+'</td><td><input class="form-control" name="musics['+track_no+']" type="text"></td></tr>';

        $('#track_list').append(row);

        $('#max_num').val(track_no);
    });

    var options = {
        url: "/api/artist/list",
        getValue: "name",
        list: {
            match: {
                enabled: true
            }
        },
        adjustWidth: false,
        theme: "bootstrap"
    };

    $("#album_artist").easyAutocomplete(options);
});
</script>

@endsection

@section('content')
<div class="container-fluid">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                アルバム編集
            </div>
            <div class="card-body">
                {{ Form::open(['route'=>['manage.album.update', $album->id], 'method'=>'PUT', 'class'=>'']) }}

                <div class="form-group row">
                    {{ Form::label('album_title', 'アルバムタイトル', ['class'=>'col-form-label col-md-4'])}}
                    <div class="col-md-8">
                        {{ Form::text('album_title', $album->getLocaleName('title', 'ja'), ['class'=>'form-control'.ViewUtil::hasErrorClass($errors, 'album_title'), 'placeholder'=>'Q-MHz']) }}
                        @include('layouts.parts.error_message', ['key'=>'album_title'])
                    </div>
                </div>

                <div class="form-group row">
                    {{ Form::label('artist', 'アーティスト', ['class'=>'col-form-label col-md-4'])}}
                    <div class="col-md-4">
                        {{ Form::text('artist_id', $album->artist->getLocaleName('name', 'ja'), ['class'=>'form-control'.ViewUtil::hasErrorClass($errors, 'artist_id'), 'id'=>'album_artist', 'placeholder'=>'Q-MHz']) }}
                        @include('layouts.parts.error_message', ['key'=>'artist_id'])
                        <span class="help-block">※アーティスト名を入力してください。</span>
                    </div>
                    <div class="col-md-4">
                        {{ Form::text('artist_name', $album->getLocaleName('artist_name', 'ja'), ['class'=>'form-control'.ViewUtil::hasErrorClass($errors, 'artist_name'), 'id'=>'album_artist_name', 'placeholder'=>'別名義']) }}
                        @include('layouts.parts.error_message', ['key'=>'artist_name'])
                        <span class="help-block">※別名義で登録したい場合は、こちらに入力</span>
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
                                @php
                                $musics = $album->musics;
                                if (old('musics') && count(old('musics')) > 10) {
                                    $max = count(old('musics'));
                                } else {
                                    $max = ($musics->count() > 0) ? $musics->max('track_no') : 0;
                                }
                                @endphp
                                @for($no = 1; $no <= $max; $no++)
                                <tr>
                                    <td>{{ $no }}</td>
                                    <td>
                                        {{ Form::text("musics[{$no}]", old('musics.'.$no) ?: ($music = $musics->firstWhere('track_no', $no)) ? $music->getLocaleName('title', 'ja') : null, ['class'=>'form-control']+(($no==1) ? ['placeholder'=>'LiVE DiVE MHz!!'] : [])) }}
                                    </td>
                                </tr>
                                @endfor
                            </tbody>
                        </table>
                        {{ Form::hidden('max_num', $max, ['id'=>'max_num']) }}
                        <button type="button" id="add_row" class="btn btn-secondary">行追加</button>
                    </div>
                </div>

                <div class="form-group row">
                    {{ Form::label('description', '詳細・アルバムに携わった人等', ['class'=>'col-form-label col-md-4'])}}
                    <div class="col-md-8">
                        {{ Form::textarea('description', $album->getLocaleText('description', 'ja'), ['class'=>'form-control', 'rows'=>'6']) }}
                    </div>
                </div>

                <h4>アフィリエイト</h4>

                <div class="form-group row">
                    {{ Form::label('affi_apple_music', 'Apple Music URL', ['class'=>'col-form-label col-md-4'])}}
                    <div class="col-md-8">
                        {{ Form::text('affi_apple_music', $album->affi_apple_music, ['class'=>'form-control'.ViewUtil::hasErrorClass($errors, 'affi_apple_music'), 'placeholder'=>'https://music.apple.com/****']) }}
                        @include('layouts.parts.error_message', ['key'=>'affi_apple_music'])
                    </div>
                </div>

                <div class="form-group row">
                    {{ Form::label('affi_amazon', 'Amazon URL', ['class'=>'col-form-label col-md-4'])}}
                    <div class="col-md-8">
                        {{ Form::text('affi_amazon', $album->affi_amazon, ['class'=>'form-control'.ViewUtil::hasErrorClass($errors, 'affi_amazon'), 'placeholder'=>'https://www.amazon.co.jp/****']) }}
                        @include('layouts.parts.error_message', ['key'=>'affi_amazon'])
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
