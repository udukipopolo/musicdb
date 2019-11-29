@extends('layouts.app')

@section('head')
{{ Html::style('css/select2.min.css') }}
{{ Html::script('js/select2.min.js') }}
<template id="add_row_template">
<tr>
    <td>
        {{ Form::text("name_template_artist_id", null, ['class'=>'col-md-6 form-control js-autocomplete', 'id'=>'id_template_artist']) }}
        {{ Form::text("name_template_artist_name", null, ['class'=>'col-md-6 form-control', 'id'=>"id_template_artist_name"]) }}
    </td>
    <td>
        {{ Form::text("name_template_part_name", null, ['class'=>'form-control']) }}
    </td>
</tr>
</template>
<script>
function setAutocomplete() {
    var options = {
        url: "/api/artist/list",
        getValue: "name",
        list: {
            match: {
                enabled: true
            }
        }
    };

    $(".js-autocomplete").easyAutocomplete(options);
}
$(document).ready(function(){
    setAutocomplete();

    $('#add_row').click(function() {
        var no = parseInt($('#max_num').val()) + 1;

        var template = $('#add_row_template').clone().html();

        template = template.replace('name_template_artist_id', 'add_artist_id['+no+']')
            .replace('id_template_artist', 'add_artist_'+no)
            .replace('name_template_artist_name', 'add_artist_name['+no+']')
            .replace('id_template_artist_name', 'add_artist_'+no+'_name')
            .replace('name_template_part_name', 'add_part_name['+no+']');

        $('#part_list').append(template);

        $('#max_num').val(no);

        setAutocomplete();
    });

});
</script>

@endsection

@section('content')
<div class="container-fluid">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                楽曲パート編集
            </div>
            <div class="card-body">
                {{ Form::open(['route'=>['manage.album.music.update', $album->id, $music->id], 'method'=>'PUT', 'class'=>'']) }}

                <div class="form-group row">
                    {{ Form::label('title', 'アルバムタイトル', ['class'=>'col-form-label col-md-4'])}}
                    <div class="col-md-8">
                        {{ Form::text('title', $album->title, ['class'=>'form-control-plaintext', 'readonly']) }}
                    </div>
                </div>

                <div class="form-group row">
                    {{ Form::label('artist', 'アルバムアーティスト', ['class'=>'col-form-label col-md-4'])}}
                    <div class="col-md-8">
                        {{ Form::text('artist_name', $album->artist_name, ['class'=>'form-control-plaintext', 'readonly']) }}
                    </div>
                </div>

                <div class="form-group row">
                    {{ Form::label('artist', 'トラックNo', ['class'=>'col-form-label col-md-4'])}}
                    <div class="col-md-8">
                        {{ Form::text('track_no', $music->track_no, ['class'=>'form-control-plaintext', 'readonly']) }}
                    </div>
                </div>

                <div class="form-group row">
                    {{ Form::label('artist', '楽曲名', ['class'=>'col-form-label col-md-4'])}}
                    <div class="col-md-8">
                        {{ Form::text('music_title', $music->title, ['class'=>'form-control-plaintext', 'readonly']) }}
                    </div>
                </div>


                <div class="form-group row">
                    {{ Form::label('parts', 'パート', ['class'=>'col-form-label col-md-4'])}}
                    <div class="col-md-8">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>アーティスト名 / 表示名(別名義)</th>
                                    <th>パート</th>
                                </tr>
                            </thead>
                            <tbody id="part_list">
                                @php
                                $parts = $music->parts;
                                if (old('add_artist_id')) {
                                    $max = count(old('add_artist_id'));
                                } elseif ($parts->count() > 0) {
                                    $max = 0;
                                } else {
                                    $max = 5;
                                }
                                @endphp

                                @foreach ($parts as $part)
                                <tr>
                                    <td>
                                        {{ Form::text("edit_artist_id[".$part->id."]", $part->artist->name, ['class'=>'col-md-6 form-control js-autocomplete'.ViewUtil::hasErrorClass($errors, 'edit_artist_id.'.$part->id), 'id'=>'edit_artist_'.$part->id]) }}
                                        {{ Form::text("edit_artist_name[".$part->id."]", $part->artist_name, ['class'=>'col-md-6 form-control'.ViewUtil::hasErrorClass($errors, 'edit_artist_name.'.$part->id), 'id'=>"edit_artist_{$part->id}_name"]) }}
                                        @include('layouts.parts.error_message', ['key'=>'edit_artist_name.'.$part->id])
                                    </td>
                                    <td>
                                        {{ Form::text("edit_part_name[".$part->id."]", $part->part_name, ['class'=>'form-control'.ViewUtil::hasErrorClass($errors, 'edit_part_name.'.$part->id)]) }}
                                        @include('layouts.parts.error_message', ['key'=>'edit_part_name.'.$part->id])
                                    </td>
                                </tr>
                                @endforeach

                                @for($no = 1; $no <= $max; $no++)
                                <tr>
                                    <td>
                                        {{ Form::text("add_artist_id[{$no}]", old('add_artist_id.'.$no), ['class'=>'col-md-6 form-control js-autocomplete'.ViewUtil::hasErrorClass($errors, 'add_artist_id.'.$no), 'id'=>'add_artist_'.$no]) }}
                                        {{ Form::text("add_artist_name[{$no}]", old('add_artist_name.'.$no), ['class'=>'col-md-6 form-control'.ViewUtil::hasErrorClass($errors, 'add_artist_name.'.$no), 'id'=>"add_artist_{$no}_name"]) }}
                                        @include('layouts.parts.error_message', ['key'=>'add_artist_name.'.$no])
                                    </td>
                                    <td>
                                        {{ Form::text("add_part_name[{$no}]", old('add_part_name.'.$no), ['class'=>'form-control'.ViewUtil::hasErrorClass($errors, 'add_part_name.'.$no)]) }}
                                        @include('layouts.parts.error_message', ['key'=>'add_part_name.'.$no])
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
