@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    アルバム管理
                </div>
                <div class="card-body">
                    @include('layouts.parts.message')

                    {{ Form::open(['route'=>['manage.album.index'], 'method'=>'GET', 'class'=>'form-inline']) }}
                        <label class="sr-only" form="name">アルバムタイトル</label>
                        {{ Form::text('title', @$input['title'], ['class'=>'form-control', 'placeholder'=>'アルバムタイトル']) }}
                        {{ Form::submit('検索', ['class'=>'btn btn-light']) }}
                        {{ Html::linkRoute('manage.album.create', '新規登録', [], ['class'=>'btn btn-primary']) }}
                    {{ Form::close() }}
                </div>
            </div>
        </div>

        @isset($albums)
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    アルバム一覧
                </div>
                <tr class="card-body">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>アルバム名</th>
                                <th>アーティスト名</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($albums as $album)
                            <tr>
                                <td>
                                    {{ Html::linkRoute('manage.album.show', $album->title, [$album->id]) }}
                                </td>
                                <td>
                                    {{ $album->artist_name }}
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>

                    {{ $albums->appends(Request::input())->links() }}
                </div>
            </div>
        </div>
        @endisset
    </div>
</div>
@endsection
