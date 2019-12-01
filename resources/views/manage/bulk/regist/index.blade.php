@extends('layouts.app')

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

                    <h3>CSVアップロード</h3>
                    {{ Form::open(['route'=>['manage.bulk.regist.csv'], 'method'=>'POST', 'class'=>'form-horizontal', 'files'=>true]) }}
                        <div class="form-group">
                        {{ Form::file('csv_file', ['class'=>'form-control-file'.ViewUtil::hasErrorClass($errors, 'csv_file')]) }}
                        <p class="help-block">※文字エンコーディングはUTF-8にしてください。</p>
                        <p class="help-block">サンプルは<a href="/sample/音楽DB一括登録用フォーマット-サンプル.csv" target="_blank">こちら</a></p>
                        @include('layouts.parts.error_message', ['key'=>'csv_file'])
                        </div>
                        {{ Form::submit('送信', ['class'=>'form-control']) }}
                    {{ Form::close() }}

                    <h3>Googleスプレッドシート</h3>
                    {{ Form::open(['route'=>['manage.bulk.regist.gss'], 'method'=>'POST', 'class'=>'form-horizontal']) }}
                        <div class="form-group">
                        {{ Form::text('url', null, ['class'=>'form-control-file'.ViewUtil::hasErrorClass($errors, 'url'), 'placeholder'=>'https://docs.google.com/spreadsheets/d/XXXXXXXXXX/edit?usp=sharing']) }}
                        <p class="help-block">※GoogleスプレッドシートのURLを貼り付けてください。</p>
                        <p class="help-block">※共有で、リンクを知っている全員が編集または閲覧可にしてください。</p>
                        <p class="help-block">サンプルは<a href="https://docs.google.com/spreadsheets/d/1yQ2Q-hUYfuE1HRKCE7Jj-PHlFn41uwd1yX-EjKH0Yo0/edit#gid=0" target="_blank">こちら</a></p>
                        @include('layouts.parts.error_message', ['key'=>'url'])
                        </div>
                        {{ Form::submit('送信', ['class'=>'form-control']) }}
                    {{ Form::close() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
