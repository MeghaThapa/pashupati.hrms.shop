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
                <h1 class="m-0 text-dark">{{ __('Edit Sub Category') }}</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item">
                        <a href="{{ route('dashboard') }}">{{ __('Dashboard') }}</a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="{{ route('subCategories.index') }}">{{ __('Sub Categories') }}</a>
                    </li>
                    <li class="breadcrumb-item active">{{ __('Edit Sub Category') }}</li>
                </ol>
            </div>
        </div>
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <div class="content">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">{{ __('Edit sub category') }}: {{ $subCategory->name }}</h3>
                <div class="card-tools">
                    <a href="{{ route('subCategories.index') }}" class="btn btn-block btn-primary">
                        <i class="fas fa-long-arrow-alt-left"></i> {{ __('Go Back') }}
                    </a>
                </div>
            </div>
            <!-- /.card-header -->
            <div class="card-body p-0">
                <form class="form-horizontal" action="{{ route('subCategories.update', $subCategory->slug) }}" method="post" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="card-body">
                        <div class="row">
                            <div class="form-group col-md-4">
                                <label for="name">{{ __('Sub Category Name') }}<span class="required-field">*</span></label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" placeholder="{{ __('Sub Category Name') }}" value="{{ $subCategory->name }}" required>
                                @error('name')
                                <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                @enderror
                            </div>
                            <div class="form-group col-md-4">
                                <label for="item">{{ __('Product Number') }}<span class="required-field">*</span></label>
                                <input type="text" class="form-control @error('pnumber') is-invalid @enderror" id="pnumber" name="pnumber" placeholder="{{ __('Product No') }}" value="{{ $subCategory->pnumber }}" required>
                                @error('name')
                                <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                @enderror
                            </div>
                            <div class="col-md-4 form-group">
                                <label for="supplier" class="col-form-label">{{ __('Supplier Name') }}<span class="required-field">*</span></label>
                                <select class="advance-select-box form-control @error('supplier') is-invalid @enderror" id="supplier" name="supplier" required data-placeholder="{{ __('Select a supplier') }}">
                                    @foreach ($suppliers as $supplier)
                                        <option value="{{ $supplier->id }}" {{ $supplier->id == $purchase->supplier_id ? 'selected' : '' }}>{{ $supplier->name }}</option>
                                    @endforeach
                                </select>
                                @error('supplier')
                                <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                @enderror
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 form-group mt-3">
                                <label for="categoryName">{{ __('Category Name') }}<span class="required-field">*</span></label>
                                <select class="advance-select-box form-control @error('categoryName') is-invalid @enderror" id="categoryName" name="categoryName"  required>
                                    <option value="" selected disabled>{{ __('Select a category') }}</option>
                                    @foreach($categories as $key => $category)
                                        <option value="{{ $category->id }}" {{ $category->id == $subCategory->category_id ? 'selected' : ''  }} >{{ $category->name }}</option>
                                    @endforeach
                                </select>
                                @error('name')
                                <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                @enderror
                            </div>
                            <div class="col-md-6 form-group">
                                <label for="sizes" class="col-form-label">{{ __('Sizes') }}<span class="required-field">*</span></label>
                                <select class="advance-select-box form-control @error('sizes') is-invalid @enderror" name="sizes[]" multiple="multiple" data-placeholder="{{ __('Select multiple sizes') }}" value="" required>
                                    @foreach ($sizes as $size)
                                        <option value="{{ $size->code }}" @foreach ($selectedSizes as $selectedSize) {{ $size->code == $selectedSize ? 'selected' : '' }} @endforeach>{{ $size->name }}({{ $size->code }})</option>
                                    @endforeach
                                </select>
                                @error('sizes')
                                <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                @enderror
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-md-12">
                                <label for="note" class="col-form-label">{{ __('Sub Category Note') }}</label>
                                <textarea class="form-control @error('note') is-invalid @enderror" id="note" name="note" placeholder="Sub category note">{{ $subCategory->note }}</textarea>
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
                                    <option value="1" {{ $subCategory->isActive() ? 'selected' : '' }}>{{ __('Active') }}</option>
                                    <option value="0" {{ $subCategory->isActive() ? '' : 'selected' }}>{{ __('Inactive') }}</option>
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
