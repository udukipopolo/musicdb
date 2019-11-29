@extends('layouts.app')

@section('head')
{{ Html::style('css/select2.min.css') }}
{{ Html::script('js/select2.min.js') }}
<script>
$(document).ready(function(){
    {{--
    $('.js-select2').select2()
        .on('select2:select', function(e) {
            var name_id = '#'+$(this).attr('id')+'_name';
            let id = e.params.data.id;
            let name = e.params.data.text;
            if (id != '') {
                $(name_id).val(name);
            } else {
                $(name_id).val('');
            }
        });
    --}}
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
        }
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
                アルバム登録
            </div>
            <div class="card-body">
                {{ Form::open(['route'=>['manage.album.store'], 'method'=>'POST', 'class'=>'']) }}

                <div class="form-group row">
                    {{ Form::label('title', 'アルバムタイトル', ['class'=>'col-form-label col-md-4'])}}
                    <div class="col-md-8">
                        {{ Form::text('title', null, ['class'=>'form-control'.ViewUtil::hasErrorClass($errors, 'title'), 'placeholder'=>'Q-MHz']) }}
                        @include('layouts.parts.error_message', ['key'=>'title'])
                    </div>
                </div>

                {{--
                <div class="form-group row">
                    {{ Form::label('artist', 'アーティスト', ['class'=>'col-form-label col-md-4'])}}
                    <div class="col-md-4">
                        {{ Form::select('artist_id', [''=>'新規登録']+$artists->toArray(), null, ['class'=>'form-control js-select2'.ViewUtil::hasErrorClass($errors, 'artist_id'), 'id'=>'album_artist']) }}
                        <span class="help-block">※登録済みのアーティストはこちらから選択</span>
                        @include('layouts.parts.error_message', ['key'=>'artist_id'])
                    </div>
                    <div class="col-md-4">
                        {{ Form::text('artist_name', null, ['class'=>'form-control'.ViewUtil::hasErrorClass($errors, 'artist_name'), 'id'=>'album_artist_name', 'placeholder'=>'Q-MHz']) }}
                        <span class="help-block">※別名義で登録したい場合は、こちらを編集</span>
                        @include('layouts.parts.error_message', ['key'=>'artist_name'])
                    </div>
                </div>
                --}}

                <div class="form-group row">
                    {{ Form::label('artist', 'アーティスト', ['class'=>'col-form-label col-md-4'])}}
                    <div class="col-md-4">
                        {{ Form::text('artist_id', null, ['class'=>'form-control'.ViewUtil::hasErrorClass($errors, 'artist_id'), 'id'=>'album_artist', 'placeholder'=>'Q-MHz']) }}
                        <span class="help-block">※登録済みのアーティストはこちらから選択</span>
                        @include('layouts.parts.error_message', ['key'=>'artist_id'])
                    </div>
                    <div class="col-md-4">
                        {{ Form::text('artist_name', null, ['class'=>'form-control'.ViewUtil::hasErrorClass($errors, 'artist_name'), 'id'=>'album_artist_name', 'placeholder'=>'別名義']) }}
                        <span class="help-block">※別名義で登録したい場合は、こちらに入力</span>
                        @include('layouts.parts.error_message', ['key'=>'artist_name'])
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
                                if (old('musics') && count(old('musics')) > 10) {
                                    $max = count(old('musics'));
                                } else {
                                    $max = 10;
                                }
                                @endphp
                                @for($no = 1; $no <= $max; $no++)
                                <tr>
                                    <td>{{ $no }}</td>
                                    <td>
                                        {{ Form::text("musics[{$no}]", old('musics.'.$no), ['class'=>'form-control']+(($no==1) ? ['placeholder'=>'LiVE DiVE MHz!!'] : [])) }}
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
                        {{ Form::textarea('description', null, ['class'=>'form-control', 'rows'=>'6']) }}
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
