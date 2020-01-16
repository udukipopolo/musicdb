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
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label class="sr-only" form="artist_name">アーティスト名</label>
                                {{ Form::text('artist_name', @$input['artist_name'], ['class'=>'form-control', 'placeholder'=>'アーティスト名']) }}
                            </div>
                            <div class="col-md-4">
                                <div class="row">
                                    <div class="btn-group btn-group-toggle" data-toggle="buttons">
                                        <label class="btn btn-light {{ (!isset($input['search_type']) || $input['search_type'] == 'fulltext') ? 'active' : '' }}">
                                            {{ Form::radio('search_type', 'fulltext', (!isset($input['search_type']) || $input['search_type'] == 'fulltext'), ['class'=>'from-check-input', 'id'=>'search_type_fulltext']) }}
                                            あいまい検索
                                        </label>
                                        <label class="btn btn-light {{ (@$input['search_type'] == 'like') ? 'active' : '' }}">
                                            {{ Form::radio('search_type', 'like', (@$input['search_type'] == 'like'), ['class'=>'from-check-input', 'id'=>'search_type_like']) }}
                                            部分一致検索
                                        </label>
                                    </div>
                                </div>
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
