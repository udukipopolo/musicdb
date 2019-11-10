@if (session('message'))
<div class="alert alert-success alert-dismissible" role="alert">
    <button type="button" class="close" data-dismiss="alert" aria-label="閉じる"><span aria-hidden="true">×</span></button>
    {{ session('message') }}
</div>
@endif
