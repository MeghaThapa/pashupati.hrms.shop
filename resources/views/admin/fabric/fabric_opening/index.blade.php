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
                <h1 class="m-0 text-dark">{{ __('Create Fabric') }}</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item">
                        <a href="{{ route('dashboard') }}">{{ __('Dashboard') }}</a>
                    </li>
                    
                    <li class="breadcrumb-item active">{{ __('Fabric Opening') }}</li>
                </ol>
            </div>
        </div>
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <div class="content">

        @if($errors->any())
            <div class="alert alert-light text-danger text-center alert-dismissible fade show" role="alert">
                <button type="button" class="btn-close close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    <span class="sr-only">Close</span>
                </button>
                @foreach ($errors->all() as $error)
                    <strong>{!! $error."<br>" !!}</strong>
                @endforeach
            </div>
        @endif

        <form action="{{ route('fabric.opening.store') }}" method="post" enctype="multipart/form-data">
            @csrf
            <div class="row">
                <div class="col-md-6">
                    <label for="">Receipt Number</label>
                    <input type="text" class="form-control" name="opening" value="opening" readonly>
                </div>
                <div class="col-md-6">
                    <label for="">Receipt Date</label>
                    <input type="date" class="form-control" name="fabric_opening_date" value="{{ date('Y-m-d') }}">
                </div>
                <div class="col-md-6">
                    <label for="">To Godam</label>
                   <select name="godam" id="godam" class="advance-select-box">
                    <option disabled selected>--Select Godam--</option>
                    @foreach ($godam as $data)
                        <option value="{{ $data->id }}">{{ $data->name }}</option>
                    @endforeach
                   </select>
                </div>
                <div class="col-md-6">
                    <label for="">Fabric Type</label>
                    <select name="type" class="form-control advance-select-box" id="type">
                        <option value="lam">Lam</option>
                        <option value="unlam">Unlam</option>
                    </select>
                </div>
                <div class="col-md-6">
                    <label for="">Choose Excel File</label>
                    <input type="file" name="file" class="form-control file-imput">
                </div>
                <div class="col-md-12 mt-3">
                    <button type="submit" class="btn btn-primary">Create Opening</button>
                </div>
            </div>
        </form>
    <div>
@endsection

@section('extra-script')
<script src="{{ asset('js/select2/select2.min.js') }}"></script>
@endsection