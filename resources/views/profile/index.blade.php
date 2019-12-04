@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">{{ Auth::user()->name }}</div>

                <div class="card-body">
                    <div class="row">
                        @include('layouts.parts.message')

                        <div class="col-md-6 col-md-offset-3 col-sm-8 col-sm-offset-2">
                            {{ Html::linkRoute('profile.edit', 'プロフィール編集', [], ['class'=>'btn btn-light btn-block']) }}
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection
