@extends('layouts.app')

@section('head')
{{ Html::style('css/handsontable.full.min.css') }}
{{ Html::script('js/handsontable.full.min.js') }}
<script>
$(function() {
    var data = [
    ];

    var container = document.getElementById('sheet');
    var hot = new Handsontable(container, {
//        data: data,
        rowHeaders: true,
        colHeaders: true,
        filters: false,
        dropdownMenu: false,
        allowInsertColumn: false,
        allowRemoveColumn: false,
        width: '100%',
        stretchHString: 'all',
        startRows: 15,
        colHeaders: [
            'アルバムタイトル',
            'アルバムアーティスト',
            'アルバムアーティスト(表示名義)',
            '楽曲トラックNO',
            '楽曲名',
            'パート',
            'アーティスト名',
            'アーティスト名(表示名義)'
        ],
        contextMenu: {
            items: {
                row_above: {
                    name: '上に行を挿入',
                    callback: function (key, normalizedSelection) {
                        var latestSelection = normalizedSelection[Math.max(normalizedSelection.length - 1, 0)];
                        this.alter('insert_row', latestSelection.start.row);
                        var col = hot.propToCol(COL_PRODUCTCODE);
                        hot.selectCell(latestSelection.start.row, col);
                    }
                },
                row_below: {
                    name: '下に行を挿入',
                    callback: function (key, normalizedSelection) {
                        var latestSelection = normalizedSelection[Math.max(normalizedSelection.length - 1, 0)];
                        this.alter('insert_row', latestSelection.end.row + 1);
                        var col = hot.propToCol(COL_PRODUCTCODE);
                        hot.selectCell(latestSelection.end.row + 1, col);
                    }
                }
            }
        }
    });
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
