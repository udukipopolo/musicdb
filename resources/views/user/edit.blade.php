@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    ユーザ編集
                </div>
                <div class="card-body">
                    {{ Form::open(['route'=>['user.update', $user->id], 'method'=>'PUT']) }}

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
                            {{ Form::checkbox('role_admin', true, $user->role_admin, ['class'=>'form-check-input', 'id'=>"check_role_admin"]) }}
                            <label class="form-check-label" for="check_role_admin">@lang('messages.role_admin')</label>
                        </div>
                    </div>

                    <div class="form-group row">
                        {{ Form::label('role_develop', __('messages.role_develop'), ['class'=>'col-form-label col-md-4'])}}
                        <div class="col-md-8">
                            {{ Form::checkbox('role_develop', true, $user->role_develop, ['class'=>'form-check-input', 'id'=>"check_role_develop"]) }}
                            <label class="form-check-label" for="check_role_develop">@lang('messages.role_develop')</label>
                        </div>
                    </div>

                    <div class="form-group row">
                        {{ Form::label('role_editor', __('messages.role_editor'), ['class'=>'col-form-label col-md-4'])}}
                        <div class="col-md-8">
                            {{ Form::checkbox('role_editor', true, $user->role_editor, ['class'=>'form-check-input', 'id'=>"check_role_editor"]) }}
                            <label class="form-check-label" for="check_role_editor">@lang('messages.role_editor')</label>
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-md-12">
                            {{ Form::submit('更新', ['class'=>'btn btn-primary']) }}
                        </div>
                    </div>

                    {{ Form::close() }}

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
