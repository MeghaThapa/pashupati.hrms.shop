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
                <li class="breadcrumb-item">{{ __('Prints and cuts') }}</li>
                <li class="breadcrumb-item active">{{ __('Create Entry') }}</li>
            </ol>
        </div>
    </div>
</div>
<!-- /.content-header -->
<div class="content">
    <div class="container-fluid">
        <div class="card">
            <div class="card-header">
                    <a class='go-back btn btn-primary'>Go Back</a>
            </div>
            <div class="table-custom card-body table-responsive">
                <form action="{{ route('prints.and.cuts.store.entry') }}" method="post">
                    @csrf
                    <div class="row">
                    <div class="col-md-6">
                        <label for="receipt_number">Receipt Number</label>
                        <input type="text" name="receipt_number" class="form-control" id="receipt_number">
                    </div>
                    <div class="col-md-6">
                        <label for="receipt_number">Date</label>
                        <input type="text" name="date" class="form-control" id="date">
                    </div>
                    <div class="col-md-6">
                        <label for="receipt_number">Date Np</label>
                        <input type="text" name="date_np" class="form-control" id="date_np">
                    </div>
                    <div class="col-md-6 mt-3">
                        <button class="btn btn-primary" type="submit">Create</button>
                    </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
@section('extra-script')
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