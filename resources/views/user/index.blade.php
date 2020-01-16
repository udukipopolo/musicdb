@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12 mb-3">
            <div class="card">
                <div class="card-header">
                    ユーザ検索
                </div>
                <div class="card-body">
                    @include('layouts.parts.message')

                    {{ Form::open(['route'=>['user.index'], 'method'=>'GET', 'class'=>'']) }}
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label class="sr-only" form="user_name">@lang('messages.user_name')</label>
                                {{ Form::text('user_name', @$input['user_name'], ['class'=>'form-control', 'placeholder'=>__('messages.user_name')]) }}
                            </div>
                            <div class="col-md-4">
                                <label class="sr-only" form="email">@lang('messages.email')</label>
                                {{ Form::text('email', @$input['email'], ['class'=>'form-control', 'placeholder'=>__('messages.email')]) }}
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                {{ Form::submit(__('messages.search'), ['class'=>'btn btn-primary']) }}
                            </div>
                        </div>
                    {{ Form::close() }}
                </div>
            </div>
        </div>

        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    検索結果
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            <table class="table table-bordred table-striped">
                                <thead>
                                    <tr>
                                        <th>@lang('messages.user_name')</th>
                                        <th>@lang('messages.email')</th>
                                        <th>@lang('messages.role_admin')</th>
                                        <th>@lang('messages.role_develop')</th>
                                        <th>@lang('messages.role_editor')</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($users as $user)
                                    <tr>
                                        <td>{{ Html::linkRoute('user.show', $user->name, [$user->id]) }}</td>
                                        <td>{{ $user->email }}</td>
                                        <td>
                                            @if ($user->role_admin)
                                            <i class="fa fa-check"></i>
                                            @endif
                                        </td>
                                        <td>
                                            @if ($user->role_develop)
                                            <i class="fa fa-check"></i>
                                            @endif
                                        </td>
                                        <td>
                                            @if ($user->role_editor)
                                            <i class="fa fa-check"></i>
                                            @endif
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                    {{ $users->appends(Request::input())->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
