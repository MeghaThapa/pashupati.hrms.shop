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
                <h1 class="m-0 text-dark">{{ __('Edit NonWovenFabric') }}</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item">
                        <a href="{{ route('dashboard') }}">{{ __('Dashboard') }}</a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="{{ route('nonwovenfabrics.index') }}">{{ __('Sub Categories') }}</a>
                    </li>
                    <li class="breadcrumb-item active">{{ __('Edit NonWovenFabric') }}</li>
                </ol>
            </div>
        </div>
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <div class="content">
        <div class="card">
            <div class="card-header">
                {{-- <h3 class="card-title">{{ __('Edit sub category') }}: {{ $nonwovenfabric->name }}</h3> --}}
                <div class="card-tools">
                    <a href="{{ route('nonwovenfabrics.index') }}" class="btn btn-block btn-primary">
                        <i class="fas fa-long-arrow-alt-left"></i> {{ __('Go Back') }}
                    </a>
                </div>
            </div>
            <!-- /.card-header -->
            <div class="card-body p-0">
                <form class="form-horizontal" action="{{ route('nonwovenfabrics.update', $nonwovenfabrics->id) }}" method="post" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="card-body">
                        <div class="row">
                            <div class="form-group col-md-12">
                                <label for="name">{{ __('NonWovenFabric Name') }}<span class="required-field">*</span></label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" placeholder="{{ __('NonWovenFabric Name') }}" value="{{ $nonwovenfabrics->name }}" required>
                                @error('name')
                                <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                @enderror
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-md-6">
                                <label for="gsm">{{ __('GSM') }}<span class="required-field">*</span></label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" id="gsm" name="gsm" placeholder="{{ __('GSM') }}" value="{{ $nonwovenfabrics->gsm }}" required>
                                @error('gsm')
                                <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                            <div class="form-group col-md-6">
                                <label for="color">{{ __('Color') }}<span class="required-field">*</span></label>
                                <input type="text" class="form-control @error('color') is-invalid @enderror" id="color" name="color" placeholder="{{ __('Color') }}" value="{{ $nonwovenfabrics->color }}" required>
                                @error('color')
                                <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                            
                        </div>
                   
                        <div class="row">
                            <div class="form-group col-md-12">
                                <label for="status" class="col-form-label">{{ __('Status') }}</label>
                                <select class="form-control" id="status" name="status">
                                    <option value="1" {{ $nonwovenfabrics->isActive() ? 'selected' : '' }}>{{ __('Active') }}</option>
                                    <option value="0" {{ $nonwovenfabrics->isActive() ? '' : 'selected' }}>{{ __('Inactive') }}</option>
                                </select>
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-sm-10">
                                <button type="submit" class="btn btn-primary"><i class="fas fa-edit"></i> {{ __('Save Changes') }}</button>
                            </div>
                        </div>
                    </div>
                    <!-- /.card-body -->
                </form>
            </div>
            <!-- /.card-body -->
        </div>
    </div>
    <!-- /.content -->
@endsection

@section('extra-script')
<script src="{{ asset('js/select2/select2.min.js') }}"></script>
@endsection
