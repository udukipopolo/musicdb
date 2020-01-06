@extends('layouts.app')

@section('head')
{{ Html::style('css/handsontable.full.min.css') }}
{{ Html::script('js/handsontable.full.min.js') }}
<script>
    $(function() {
        var hotElement = $('#sheet');
        var hotSettings = {
            colHeaders: [
                'album_title',
                'album_artist'
            ]
        };
        var hot = new Handsontable(hotElement, hotSettings);
    });
</script>

@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    一括登録
                </div>
                <div class="card-body">
                    @include('layouts.parts.message')

                    <div class="col-md-12">
                        <div id="sheet"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
