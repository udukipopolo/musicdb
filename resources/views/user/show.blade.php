@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    ユーザ詳細
                    <div class="btn-toolbar float-right" role="toolbar">
                        <div class="btn-group" role="group">
                            <a href="{{ route('user.edit', [$user->id]) }}" class="btn btn-success">@lang('messages.edit')</a>
                        </div>
                        <div class="btn-group" role="group">
                            {{ Form::open(['route'=>['user.destroy', $user->id], 'method'=>'DELETE', 'onsubmit'=>'return confirm("削除しますか？");']) }}
                            {{ Form::submit(__('messages.delete'), ['class'=>'btn btn-danger']) }}
                            {{ Form::close() }}
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    @include('layouts.parts.message')

                    <div class="form-group row">
                        {{ Form::label('user_name', __('messages.user_name'), ['class'=>'col-form-label col-md-4'])}}
                        <div class="col-md-8">
                            {{ Form::text('user_name', $user->name, ['class'=>'form-control-plaintext', 'readonly']) }}
                        </div>
                    </div>

                    <div class="form-group row">
                        {{ Form::label('email', __('messages.email'), ['class'=>'col-form-label col-md-4'])}}
                        <div class="col-md-8">
                            {{ Form::text('email', $user->email, ['class'=>'form-control-plaintext', 'readonly']) }}
                        </div>
                    </div>

                    <div class="form-group row">
                        {{ Form::label('role_admin', __('messages.role_admin'), ['class'=>'col-form-label col-md-4'])}}
                        <div class="col-md-8">
                            @if ($user->role_admin)
                            <p class="form-control-plaintext"><i class="fa fa-check"></i></p>
                            @endif
                        </div>
                    </div>

                    <div class="form-group row">
                        {{ Form::label('role_develop', __('messages.role_develop'), ['class'=>'col-form-label col-md-4'])}}
                        <div class="col-md-8">
                            @if ($user->role_develop)
                            <p class="form-control-plaintext"><i class="fa fa-check"></i></p>
                            @endif
                        </div>
                    </div>

                    <div class="form-group row">
                        {{ Form::label('role_editor', __('messages.role_editor'), ['class'=>'col-form-label col-md-4'])}}
                        <div class="col-md-8">
                            @if ($user->role_editor)
                            <p class="form-control-plaintext"><i class="fa fa-check"></i></p>
                            @endif
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
