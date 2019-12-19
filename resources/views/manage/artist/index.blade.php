@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    アーティスト管理
                </div>
                <div class="card-body">
                    @include('layouts.parts.message')

                    {{ Form::open(['route'=>['manage.artist.index'], 'method'=>'GET', 'class'=>'form-inline']) }}
                        <label class="sr-only" form="name">アーティスト名</label>
                        {{ Form::text('name', @$input['name'], ['class'=>'form-control', 'placeholder'=>'アーティスト名']) }}
                        {{ Form::submit('検索', ['class'=>'btn btn-light']) }}
                        {{ Html::linkRoute('manage.artist.create', '新規登録', [], ['class'=>'btn btn-primary']) }}
                    {{ Form::close() }}
                </div>
            </div>
        </div>

        @isset($artists)
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    アーティスト一覧
                </div>
                <div class="card-body">
                    <div class="row">
                    @foreach ($artists as $artist)
                    <div class="col-lg-3 col-md-4 col-sm-6">
                        {{ Html::linkRoute('manage.artist.show', $artist->getLocaleName('name', 'ja'), [$artist->id]) }}
                    </div>
                    @endforeach
                    </div>

                    {{ $artists->appends(Request::input())->links() }}
                </div>
            </div>
        </div>
        @endisset
    </div>
</div>
@endsection
