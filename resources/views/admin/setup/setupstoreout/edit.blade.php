@extends('layouts.admin')

@section('content')
    <!-- Content Header (Page header) -->
    <div class="content-header mb-4">
        <div class="row align-items-center">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">{{ __('Edit storeout') }}</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item">
                        <a href="{{ route('dashboard') }}">{{ __('Dashboard') }}</a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="{{ route('admin.setup') }}">{{ __('Setup') }}</a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="{{ route('storeoutsetup.index') }}">{{ __('storeoutsetup') }}</a>
                    </li>
                    <li class="breadcrumb-item active">{{ __('Edit storeout') }}</li>
                </ol>
            </div>
        </div>
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <div class="content">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">{{ __('Edit storeout') }}: {{ $storeout->name }}</h3>
                <div class="card-tools">
                    <a href="{{ route('storeoutsetup.index') }}" class="btn btn-block btn-primary">
                        <i class="fas fa-long-arrow-alt-left"></i> {{ __('Go Back') }}
                    </a>
                </div>
            </div>
            <!-- /.card-header -->
            <div class="card-body p-0">
                <form class="form-horizontal" action="{{ route('storeoutsetup.update', $storeout->slug) }}" method="post">
                    @csrf
                    @method('PUT')
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 form-group">
                                <label for="name">{{ __('storeout Name') }}<span class="required-field">*</span></label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" placeholder="{{ __('storeout Name') }}" value="{{ $storeout->name }}" required>
                                @error('name')
                                <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                @enderror
                            </div>
                            <div class="col-md-6 form-group">
                                <label for="storeoutCode">{{ __('storeout Code') }}<span class="required-field">*</span></label>
                                <input type="text" class="form-control @error('storeoutCode') is-invalid @enderror" id="storeoutCode" name="storeoutCode" placeholder="{{ __('storeout Code') }}" value="{{ $storeout->code }}" required>
                                @error('storeoutCode')
                                <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                @enderror
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-md-12">
                                <label for="note" class="col-form-label">{{ __('storeout Note') }}</label>
                                <textarea class="form-control @error('note') is-invalid @enderror" id="note" name="note" placeholder="{{ __('storeout Note') }}">{{ $storeout->note }}</textarea>
                                @error('note')
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
                                    <option value="1" {{ $storeout->isActive() ? 'selected' : '' }}>{{ __('Active') }}</option>
                                    <option value="0" {{ $storeout->isActive() ? '' : 'selected' }}>{{ __('Inactive') }}</option>
                                </select>
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-sm-10">
                                <button type="submit" class="btn btn-primary"><i class="fas fa-edit"></i> {{ __('Save Chaages') }}</button>
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


