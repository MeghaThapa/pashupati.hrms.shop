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
                <h1 class="m-0 text-dark">{{ __('Edit Processing') }}</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item">
                        <a href="{{ route('dashboard') }}">{{ __('Dashboard') }}</a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="{{ route('processing.index') }}">{{ __('Processing Products') }}</a>
                    </li>
                    <li class="breadcrumb-item active">{{ __('Edit Processing') }}</li>
                </ol>
            </div>
        </div>
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <div class="content">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">{{ __('Edit processing product for purcahse') }}: {{ AppHelper::instance()->pruchaseCode($processing->purchase->purchase_code) }}</h3>
                <div class="card-tools">
                    <a href="{{ route('processing.index') }}" class="btn btn-block btn-primary">
                        <i class="fas fa-long-arrow-alt-left"></i> {{ __('Go Back') }}
                    </a>
                </div>
            </div>
            <!-- /.card-header -->
            <div class="card-body p-0">
                <form class="form-horizontal" action="{{ route('processing.update', $processing->slug) }}" method="post" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="card-body">
                        <div class="row">
                            <div class="form-group col-md-12">
                                <label for="purcahseProduct" class="col-form-label">{{ __('Purchased Product') }}<span class="required-field">*</span></label>
                                <select class="advance-select-box form-control @error('purcahseProduct') is-invalid @enderror" id="purcahseProduct" name="purcahseProduct" data-placeholder="{{ __('Select a purchased product') }}" required>
                                    @foreach ($purchases as $purchase)
                                        <option value="{{ $purchase->id }}" notennotefdfad>{{ AppHelper::instance()->pruchaseCode($purchase->purchase_code) }}</option>
                                    @endforeach
                                </select>
                                @error('purcahseProduct')
                                <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                @enderror
                            </div>

                        </div>
                        <div class="row">
                            @foreach ($processingSteps as $step)
                                <div class="form-group col-md-6">
                                    <label for="Staff" class="col-form-label">{{ $step->step_name }}<span class="required-field">*</span></label>
                                    <select class="advance-select-box form-control @error('processingStaff.*') is-invalid @enderror" id="processing-step-{{  $step->step_id }}" name="processingStaff{{  $step->step_id }}[]" multiple="multiple" data-placeholder="{{ __('Select staff for') }} {{ $step->step_name }}" required>
                                        @foreach ($staff as $member)
                                            <option value="{{ $member->id }}" @if(($processing->selectedStaff()->where('processing_step_id', $step->step_id)->get())->contains('id', $member->id)) selected @endif>{{ $member->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('processingStaff.*')
                                    <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                    @enderror
                                </div>
                            @endforeach
                        </div>
                        <div class="row">
                            <div class="col-md-6 form-group">
                                <label for="startDate">{{ __('Processing Start Date') }}<span class="required-field">*</span></label>
                                <input type="date" class="form-control @error('startDate') is-invalid @enderror" id="startDate" name="startDate"  value="{{$processing->start_date}}" required>
                                @error('startDate')
                                <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="endDate">{{ __('Processing End Date') }}</label>
                                <input type="date" class="form-control @error('endDate') is-invalid @enderror" id="endDate" name="endDate"  value="{{$processing->end_date}}" >
                                @error('endDate')
                                <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                @enderror
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 form-group">
                                <label for="note" class="col-form-label">{{ __('Processing Note') }}</label>
                                <textarea class="form-control @error('note') is-invalid @enderror" id="note" name="note" placeholder="{{ __('Processing Note') }}">{{ $processing->note }}</textarea>
                                @error('note')
                                <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                @enderror
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 form-group">
                                <label for="attchedPic" class="col-form-label">{{ __('Processing Image') }}</label>
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
                                    @if($processing->processing_image)
                                        <img src="{{ $processing->imagepath() }}" id="attached-preview-img" class="mt-3"/>
                                    @else
                                        <img src="{{ asset('img/placeholder.png') }}" id="attached-preview-img" class="mt-3"/>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-6 form-group">
                                <label for="status" class="col-form-label">{{ __('Status') }}</label>
                                <select class="form-control" id="status" name="status">
                                    <option value="1" {{ $processing->isActive() ? 'selected' : '' }}>{{ __('Active') }}</option>
                                    <option value="0" {{ $processing->isActive() ? '' : 'selected' }}>{{ __('Inactive') }}</option>
                                </select>
                            </div>
                        </div>
                        <div class="row">
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
