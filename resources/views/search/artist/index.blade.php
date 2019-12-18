@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    アーティスト検索
                </div>
                <div class="card-body">
                    @include('layouts.parts.message')

                    {{ Form::open(['route'=>['search.artist.index'], 'method'=>'GET', 'class'=>'']) }}
                        <div class="row">
                            <div class="col-md-4">
                                <label class="sr-only" form="artist_name">アーティスト名</label>
                                {{ Form::text('artist_name', @$input['artist_name'], ['class'=>'form-control', 'placeholder'=>'アーティスト名']) }}
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                {{ Form::submit('検索', ['class'=>'btn btn-primary']) }}
                                {{ Html::linkRoute('search.artist.index', 'クリア', [], ['class'=>'btn btn-light']) }}
                            </div>
                        </div>
                    {{ Form::close() }}
                </div>
            </div>
        </div>

        @isset($artists)
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    検索結果
                </div>
                <div class="card-body">
                    <div class="row">
                        @foreach ($artists as $artist)
                        <div class="col-lg-3 col-md-4 col-sm-6">
                            {{ Html::linkRoute('search.artist.show', $artist->getLocaleName('name'), [$artist->id]) }}
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
