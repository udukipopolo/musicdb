@extends('layouts.app')

@section('head')
<script>
    $(function() {
        $('.collapse').collapse();
    });
</script>
@endsection

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">FAQ</div>

                <div class="card-body">
                    <div class="accordion" id="accordionFAQ">

                        <div class="card">
                            <div class="card-header" id="h-1">
                                <h5 class="mb-0">
                                    <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#c-1" aria-expanded="true" aria-controls="c-1">
                                        Q.Touch the MUSICって？
                                    </button>
                                </h5>
                            </div>
                            <div id="c-1" class="collapse" aria-labelledby="h-1" data-parent="#accordionFAQ">
                                <div class="card-body">
                                    A.音楽データベースうんぬん。
                                </div>
                            </div>
                        </div>

                        <div class="card">
                            <div class="card-header" id="h-2">
                                <h5 class="mb-0">
                                    <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#c-2" aria-expanded="true" aria-controls="c-2">
                                        Q.使い方
                                    </button>
                                </h5>
                            </div>
                            <div id="c-2" class="collapse" aria-labelledby="h-2" data-parent="#accordionFAQ">
                                <div class="card-body">
                                    A.ポチポチやって
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
