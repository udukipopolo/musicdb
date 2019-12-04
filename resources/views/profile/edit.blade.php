@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">{{ Auth::user()->name }}</div>

                <div class="card-body">
                    {{ Form::open(['route'=>['profile.update'], 'method'=>'PUT']) }}

                        <div class="form-group row">
                            <label for="name" class="col-md-4 col-form-label text-md-right">{{ __('Name') }}</label>

                            <div class="col-md-6">
                                {{ Form::text('name', Auth::user()->name, ['class'=>"form-control".ViewUtil::hasErrorClass($errors, 'name')]) }}
                                @include('layouts.parts.error_message', ['key'=>'name'])
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="email" class="col-md-4 col-form-label text-md-right">{{ __('E-Mail Address') }}</label>

                            <div class="col-md-6">
                                {{ Form::text('email', Auth::user()->email, ['class'=>"form-control".ViewUtil::hasErrorClass($errors, 'email')]) }}
                                @include('layouts.parts.error_message', ['key'=>'email'])
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="password" class="col-md-4 col-form-label text-md-right">{{ __('Password') }}</label>

                            <div class="col-md-6">
                                {{ Form::password('password', ['class'=>"form-control".ViewUtil::hasErrorClass($errors, 'password')]) }}
                                <p class="help-block">※変更する場合のみ入力してください。</p>
                                @include('layouts.parts.error_message', ['key'=>'password'])
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="password-confirm" class="col-md-4 col-form-label text-md-right">{{ __('Confirm Password') }}</label>

                            <div class="col-md-6">
                                <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password">
                            </div>
                        </div>

                        <div class="form-group row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Register') }}
                                </button>
                            </div>
                        </div>

                    {{ Form::close() }}
                </div>
            </div>
        </div>

    </div>
</div>
@endsection
