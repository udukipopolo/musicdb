@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h3 class="mr-auto p-3">アーティスト詳細</h3>
                <div class="btn-group float-right" role="group">
                    <a href="{{ route('manage.artist.edit', [$artist->id]) }}" class="btn btn-success">編集</a>
                </div>
                <div class="clearfix"></div>
            </div>
            <div class="card-body">
                @include('layouts.parts.message')

                <div class="form-group row">
                    {{ Form::label('name', 'アーティスト名', ['class'=>'col-form-label col-md-4'])}}
                    <div class="col-md-8">
                        {{ Form::text('name', $artist->name, ['class'=>'form-control-plaintext', 'readonly']) }}
                    </div>
                </div>

                <div class="form-group row">
                    {{ Form::label('belonging', '所属事務所', ['class'=>'col-form-label col-md-4'])}}
                    <div class="col-md-8">
                        {{ Form::textarea('belonging', $artist->belonging, ['class'=>'form-control-plaintext', 'cols'=>'6', 'readonly']) }}
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
@endsection
