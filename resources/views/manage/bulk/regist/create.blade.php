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
        stretchH: 'all',
        startRows: 15,
        wordWrap: false,
        columns: [
            {
                type: 'text'
            },
            {
                type: 'text'
            },
            {
                type: 'text'
            },
            {
                type: 'numeric'
            },
            {
                type: 'text'
            },
            {
                type: 'text'
            },
            {
                type: 'text'
            },
            {
                type: 'text'
            },
            {
                type: 'text',
                readOnly: true
            }
        ],
        colHeaders: [
            'アルバムタイトル',
            'アルバムアーティスト',
            'アルバムアーティスト(表示名義)',
            '楽曲トラックNO',
            '楽曲名',
            'パート',
            'アーティスト名',
            'アーティスト名(表示名義)',
            '結果'
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
                },
                remove_row: {
                    name: '行を削除'
                }
            }
        }
    });

    $('#btn-add-row').click(function() {
        var add_row_num = $('#add_row_num').val();
        for(num = 1; num <= add_row_num; num++) {
            hot.alter('insert_row', hot.countRows());
        }
    });

    $('#btn-regist').click(function() {
        var datas = hot.getData();
        console.log(datas);
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url: "{{ route('manage.bulk.regist.store') }}",
            type: 'POST',
            data: {datas: datas},
            dataType: 'json'
        }).done(function(data) {
            if (data.status == 'success') {
                alert('@lang("messages.bulk_regist.complete")');
                data.result.forEach(function(value, index) {
                    var mes = '';
                    var color = 'black';
                    switch (value) {
                        case 0:
                            mes = '';
                            color = 'black';
                            break;
                        case 1:
                            mes = 'OK';
                            color = 'blue';
                            break;
                        case 2:
                            mes = 'NG';
                            color = 'red';
                            break;
                        case 3:
                            mes = 'DUP';
                            color = 'yellow';
                            break;
                    }
                    hot.setDataAtCell(index, 8, mes);
                    var cell = hot.getCell(index, 8);
                    cell.addClass(color);
                });
            } else {
                if (data.error_message) {
                    alert(data.error_message);
                    console.log(data.errors);
                } else {
                    alert('@lang("messages.bulk_regist.failed")');
                }
            }
        }).fail(function(XMLHttpRequest, textStatus, errorThrown) {
            alert('@lang("messages.bulk_regist.failed")');
        });
    });

    $('#btn-clear').click(function() {
        hot.clear();
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

                    <div class="col-md-12">
                        <div class="btn-toolbar" role="toolbar">
                            <div class="input-group mr-2">
                                {{ Form::number('add_row_num', 1, ['class'=>'form-control', 'id'=>'add_row_num', 'min'=>'1']) }}
                                <div class="input-group-append">
                                    <button class="btn btn-outline-secondary" type="button" id="btn-add-row">行追加</button>
                                </div>
                            </div>
                            <div class="btn-group mr-2">
                                <button class="btn btn-primary" type="button" id="btn-regist">登録</button>
                            </div>
                            <div class="btn-group">
                                <button class="btn btn-light" type="button" id="btn-clear">クリア</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
