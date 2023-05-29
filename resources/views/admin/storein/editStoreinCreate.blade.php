@extends('layouts.admin')

@section('extra-style')
    <link href="{{ asset('css/select2/select2.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('css/select2/select2-bootstrap4.css') }}" rel="stylesheet" />
@endsection

@section('content')
    <div class="content-header mb-4">
        <div class="row align-items-center">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">{{ __('Create Storein') }}</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item">
                        <a href="{{ route('dashboard') }}">{{ __('Dashboard') }}</a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="{{ route('storein.index') }}">{{ __('Storein') }}</a>
                    </li>
                    <li class="breadcrumb-item active">{{ __('Create Storein') }}</li>
                </ol>
            </div>
        </div>
    </div>
    <div class="content">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">{{ __('Add a new Storein') }}</h3>
                <div class="card-tools">
                    <a href="{{ route('storein.index') }}" class="btn btn-block btn-primary">
                        <i class="fas fa-long-arrow-alt-left"></i> {{ __('Go Back') }}
                    </a>
                </div>
            </div>
            <div class="card-body p-0">
                <form action="{{ route('storein.updateStorein', ['storein_id' => $storeinData->id]) }}" method="POST">
                    @csrf
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4 form-group">

                                <input type="text" name="storein_id" value="{{ $storeinData->id }}" hidden>
                                <label for="srno" class="col-form-label">{{ __('SR No.') }}<span
                                        class="required-field">*</span></label>
                                <input type="number" class="form-control @error('srno') is-invalid @enderror"
                                    id="srno" name="srno" value="{{ $storeinData->sr_no }}" required>
                                @error('srno')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="col-md-4 form-group">
                                <label for="billno" class="col-form-label">{{ __('Bill No.') }}</label>
                                <input type="number" class="form-control @error('billno') is-invalid @enderror"
                                    id="billno" name="billno" value="{{ $storeinData->bill_no }}">
                                @error('billno')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="col-md-4 form-group">
                                <label for="ppno" class="col-form-label">{{ __('PP No.') }}</label>
                                <input type="number" class="form-control @error('ppno') is-invalid @enderror"
                                    id="ppno" name="ppno" value="{{ $storeinData->pp_no }}">
                                @error('ppno')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4 form-group">
                                <label for="purchaseDate" class="col-form-label">{{ __('Date') }}<span
                                        class="required-field">*</span></label>
                                <input type="date" class="form-control @error('purchaseDate') is-invalid @enderror"
                                    id="purchaseDate" name="purchaseDate" value="{{ $storeinData->purchase_date }}"
                                    required>
                                @error('purchaseDate')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="col-md-4 form-group">
                                <label for="type" class="col-form-label">{{ __('Type') }}<span
                                        class="required-field">*</span>
                                </label>
                                <a href="#" class="col-md-1 btn btn-primary dynamic-btn" data-toggle="modal"
                                    data-target="#exampleModal" style="margin-top:0 !important; top:0;float:right;">
                                    <i class="fas fa-plus"
                                        style="display:flex;align-items: center;justify-content: center;"></i>
                                </a>
                                <select class="advance-select-box form-control @error('type') is-invalid @enderror"
                                    id="type" name="storein_id" required>
                                    <option value="" selected disabled>{{ __('Select a store in type') }}</option>
                                    @foreach ($storeintype as $type)
                                        <option {{ $storeinData->storein_id == $type->id ? 'selected' : '' }}
                                            value="{{ $type->id }}">
                                            {{ $type->name }}</option>
                                    @endforeach

                                    {{-- <option value="">cvghjk</option> --}}
                                </select>
                                @error('name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>


                            <div class="col-md-4 form-group">
                                <label for="supplier" class="col-form-label">{{ __('Supplier') }}<span
                                        class="required-field">*</span>
                                </label>
                                <a href="#" class="col-md-1 btn btn-primary dynamic-btn" data-toggle="modal"
                                    data-target="#exampleModalsupplier"
                                    style="margin-top:0 !important; top:0;float:right;">
                                    <i class="fas fa-plus"
                                        style="display:flex;align-items: center;justify-content: center;"></i>
                                </a>
                                <select class="advance-select-box form-control @error('supplier') is-invalid @enderror"
                                    id="supplier" name="supplier_id" required>
                                    <option value="" selected disabled>{{ __('Select a supplier') }}</option>
                                    @foreach ($suppliers as $supplier)
                                        <option value="{{ $supplier->id }}"
                                            {{ $storeinData->supplier_id == $supplier->id ? 'selected' : '' }}>
                                            {{ $supplier->name }}</option>
                                    @endforeach
                                </select>
                                @error('supplier')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror

                            </div>

                        </div>
                        <div>
                            <center>
                                <button type="submit" class="btn btn-primary">{{ __('Submit') }}</button>
                            </center>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
@section('extra-script')
    <script src="{{ asset('js/select2/select2.min.js') }}"></script>
    <script src="{{ asset('js/storein.js') }}"></script>
@endsection
