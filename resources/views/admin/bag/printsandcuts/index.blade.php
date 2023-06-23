@extends('layouts.admin')
@section('extra-style')
<link href="{{ asset('css/select2/select2.min.css') }}" rel="stylesheet" />
<link href="{{ asset('css/select2/select2-bootstrap4.css') }}" rel="stylesheet" />


@endsection
@section('content')
<!-- Content Header (Page header) -->
<div class="content-header mb-4">
    <div class="row align-items-center">
        <div class="col-sm-6">
            <h1 class="m-0 text-dark">{{ __('Prints and Cuts') }}</h1>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item">
                    <a href="{{ route('dashboard') }}">{{ __('Dashboard') }}</a>
                </li>
                <li class="breadcrumb-item active">{{ __('Prints and cuts') }}</li>
            </ol>
        </div>
    </div>
</div>
<!-- /.content-header -->
<div class="content">
    <div class="container-fluid">
        <div class="card">
            <div class="card-header">
                {{-- message for success --}}
                    <div id="success_msg" class="alert alert-success mt-2" hidden></div>
                    <div id="error_msg" class="alert alert-danger mt-2" hidden></div>
                    <button class='go-back btn btn-primary float-right'>Go Back</button>
            </div>
            <div class="card-body">
                <form method="post">
                    @csrf
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
@section('extra-script')
<script>
    $(document).on("click",".go-back",function(){
        history.back();
    });
</script>
@if(session()->has('message'))
<script>
    toastr.success("{{ session()->get('message') }}");
</script>
@elseif(session()->has('message_err'))
<script>
    toastr.error("{{ session()->get('message_err') }}");
</script>
@endif
@endsection