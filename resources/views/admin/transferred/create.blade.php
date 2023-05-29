@php
    use \App\Helpers\AppHelper;
@endphp

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
                <h1 class="m-0 text-dark">{{ __('Create Transferred Product') }}</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item">
                        <a href="{{ route('dashboard') }}">{{ __('Dashboard') }}</a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="{{ route('transferred.index') }}">{{ __('Transferred Products') }}</a>
                    </li>
                    <li class="breadcrumb-item active">{{ __('Create Product') }}</li>
                </ol>
            </div>
        </div>
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <div class="content">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">{{ __('Add a new transferred product') }}</h3>
                <div class="card-tools">
                    <a href="{{ route('transferred.index') }}" class="btn btn-block btn-primary">
                        <i class="fas fa-long-arrow-alt-left"></i> {{ __('Go Back') }}
                    </a>
                </div>
            </div>
            <!-- /.card-header -->
            <div class="card-body p-0">
                <form class="form-horizontal" action="{{ route('transferred.store') }}" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12 form-group">
                                <label for="finishedProduct" class="col-form-label">{{ __('Finished Product') }}<span class="required-field">*</span></label>
                                <select class="advance-select-box finished-select form-control @error('finishedProduct') is-invalid @enderror" id="finishedProduct" name="finishedProduct" required>
                                    <option value="" selected disabled>{{ __('Select a purchase') }}</option>
                                    @foreach ($finisedProducts as $finishedPro)
                                        <option value="{{ $finishedPro->id }}">{{ AppHelper::instance()->finishedCode($finishedPro->finished_code) }}</option>
                                    @endforeach
                                </select>
                                @error('finishedProduct')
                                <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                @enderror
                            </div>
                        </div>
                        <div class="row" id="dynamic-sizes">
                        </div>
                        <div class="row">
                            <div class="col-md-4 form-group">
                                <label for="showroom" class="col-form-label">{{ __('Showroom') }}<span class="required-field">*</span></label>
                                <select class="advance-select-box form-control @error('showroom.*') is-invalid @enderror" name="showroom" id="showroom" required>
                                    <option value="" selected disabled>{{ __('Select a showroom') }}</option>
                                    @foreach ($showrooms as $showroom)
                                        <option value="{{ $showroom->id }}">{{ $showroom->name }}</option>
                                    @endforeach
                                </select>
                                @error('showroom')
                                <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                @enderror
                            </div>
                            <div class="col-md-4 form-group">
                                <label for="cartoonNumber" class="col-form-label">{{ __('Cartoon/Box Number') }}<span class="required-field">*</span></label>
                                <input type="text" class="form-control @error('cartoonNumbers') is-invalid @enderror" id="cartoonNumber" name="cartoonNumber" placeholder="{{ __('Cartoon/Box Number') }}" value="{{ old('cartoonNumber') }}"  required>
                                @error('cartoonNumber')
                                <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                @enderror
                            </div>
                            <div class="col-md-4 form-group mt-12">
                                <label for="transferredDate">{{ __('Transferred Date') }}<span class="required-field">*</span></label>
                                <input type="date" class="form-control @error('transferredDate') is-invalid @enderror" id="transferredDate" name="transferredDate"  value="{{old('transferredDate')}}" required>
                                @error('transferredDate')
                                <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                @enderror
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-md-12">
                                <label for="note" class="col-form-label">{{ __('Transferred Note') }}</label>
                                <textarea class="form-control @error('note') is-invalid @enderror" id="note" name="note" placeholder="{{ __('Transferred Note') }}">{{ old('note') }}</textarea>
                                @error('note')
                                <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                @enderror
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 form-group">
                                <label for="attchedPic" class="col-form-label">{{ __('Transferred Image') }}</label>
                                <div class="custom-file">
                                    <input type="file" class="custom-file-input @error('attchedPic') is-invalid @enderror" id="attached-image" name="attchedPic">
                                    <label class="custom-file-label" for="attchedPic">{{ __('Choose file') }}</label>
                                    @error('attchedPic')
                                    <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                    @enderror
                                </div>
                                <div class="image-preview">
                                    <img src="" id="attached-preview-img" class="mt-3" />
                                </div>
                            </div>
                            <div class="col-md-6 form-group">
                                <label for="status" class="col-form-label">{{ __('Status') }}</label>
                                <select class="form-control" id="status" name="status">
                                    <option value="1">{{ __('Active') }}</option>
                                    <option value="0">{{ __('Inactive') }}</option>
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-10">
                                <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> {{ __('Save Product') }}</button>
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
<script src="{{ asset('js/transferred-product.js') }}"></script>
@endsection
