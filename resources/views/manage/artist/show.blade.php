@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                アーティスト詳細
                <div class="nav pull-right">
                    <li>
                        <a href="{{ route('manage.artist.edit', [$artist->id]) }}" class="btn btn-success"><i class="fa fa-edit"></i></a>
                    </li>
                </div>
            </div>
            <div class="card-body">
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
