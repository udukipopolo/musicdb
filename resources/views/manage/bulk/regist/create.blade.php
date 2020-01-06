@extends('layouts.app')

@section('head')
{{ Html::style('css/handsontable.full.min.css') }}
{{ Html::script('js/handsontable.full.min.js') }}
<script>
var data = [
  ['', 'Ford', 'Tesla', 'Toyota', 'Honda'],
  ['2017', 10, 11, 12, 13],
  ['2018', 20, 11, 14, 13],
  ['2019', 30, 15, 12, 13]
];

var container = document.getElementById('sheet');
var hot = new Handsontable(container, {
  data: data,
  rowHeaders: true,
  colHeaders: true,
  filters: true,
  dropdownMenu: true
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
