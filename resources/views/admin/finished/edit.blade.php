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
                <h1 class="m-0 text-dark">{{ __('Edit Finished Product') }}</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item">
                        <a href="{{ route('dashboard') }}">{{ __('Dashboard') }}</a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="{{ route('finished.index') }}">{{ __('Finished Products') }}</a>
                    </li>
                    <li class="breadcrumb-item active">{{ __('Edit Product') }}</li>
                </ol>
            </div>
        </div>
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <div class="content">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">{{ __('Edit finished product') }}: {{ $product->finished_code }}</h3>
                <div class="card-tools">
                    <a href="{{ route('finished.index') }}" class="btn btn-block btn-primary">
                        <i class="fas fa-long-arrow-alt-left"></i> {{ __('Go Back') }}
                    </a>
                </div>
            </div>
            <!-- /.card-header -->
            <div class="card-body p-0">
                <form class="form-horizontal" action="{{ route('finished.update', $product->slug) }}" method="post" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 form-group">
                                <label for="processingProduct" class="col-form-label">{{ __('Processing Product') }}<span class="required-field">*</span></label>
                                <select class="advance-select-box purchase-select form-control @error('processingProduct') is-invalid @enderror" id="processingProduct" name="processingProduct" required>
                                    <option value="" selected disabled>Select a processing product</option>
                                    @foreach ($processingProducts as $processingProduct)
                                        <option value="{{ $processingProduct->id }}" {{ $processingProduct->id == $product->porcessing_pro_id ? 'selected' : ''   }}>{{ AppHelper::instance()->processingCode ($processingProduct->processing_code) }}</option>
                                    @endforeach
                                </select>
                                @error('processingProduct')
                                <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                @enderror
                            </div>
                            <div class="col-md-6 form-group">
                                <label for="productType" class="col-form-label">{{ __('Product Type') }}<span class="required-field">*</span></label>
                                <select class="advance-select-box size-select form-control @error('productType') is-invalid @enderror" id="productType" name="productType" required>
                                    <option value="" selected disabled>{{ __('Select a product Type') }}</option>
                                    @foreach ($subCategories as $category)
                                        <option value="{{ $category->id }}" {{ $category->id == $product->sub_cat_id ? 'selected' : '' }}>{{ $category->name }}</option>
                                    @endforeach
                                </select>
                                @error('productType')
                                <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                @enderror
                            </div>
                        </div>
                        <div class="row existing-products" >
                            @foreach ($product->usedProducts as $purchasePro)
                                <div class="col-md-3">
                                    <label for="productName" class="col-form-label">{{ __('Product Name') }}</label>
                                    <input type="text" class="form-control @error('products.*') is-invalid @enderror" id="products" name="products[]" placeholder="{{ __('Product Name') }}" value="{{ $purchasePro->purchaseProduct->product_name }}" readonly>
                                </div>
                                <div class="col-md-3">
                                    <label for="currentQuantites" class="col-form-label">{{ __('Purchase Quantity') }}</label>
                                    <input type="text" class="form-control @error('currentQuantites') is-invalid @enderror" id="currentQuantites" name="currentQuantites[]" placeholder="{{ __('Purchase Quantity') }}" value="{{ $purchasePro->purchaseProduct->quantity }}  {{ $purchasePro->unit }}" readonly>
                                </div>
                                <div class="col-md-3">
                                    <label for="availableQuantity" class="col-form-label">{{ __('Available Quantity') }}</label>
                                    <input type="text" class="form-control @error('availableQuantites') is-invalid @enderror" id="availableQuantites" name="availableQuantites[]" placeholder="{{ __('Available Quantity') }}" value="{{ $purchasePro->purchaseProduct->availableQuantity() }}  {{ $purchasePro->unit }}" readonly>
                                </div>
                                <div class="col-md-3">
                                    <label for="usedQuantities" class="col-form-label">{{ __('Used Quantity') }}<span class="required-field">*</span></label>
                                    <input type="number" step="any" min="1" max="" class="form-control @error('usedQuantities.*') is-invalid @enderror" id="usedQuantities" value="{{  $purchasePro->used_quantity }}" name="usedQuantities[]" placeholder="{{__('Used Quantity')}}" required>
                                    @error('usedQuantities.*')
                                    <span class="invalid-feedback" role="alert">
                                                <strong>{{$message}}</strong>
                                            </span>
                                    @enderror
                                </div>
                            @endforeach
                        </div>
                        <div class="row" id="dynamic-products">
                        </div>
                        <div class="existing-sizes mt-2">
                            <label for="productSizes" class="col-form-label">{{ __('Quantities for different sizes') }}<span class="required-field">*</span></label>
                            <div class="row">
                                @for($i = 0; $i < count($sizes); $i++)
                                    <div class="col-md-3 form-group">
                                        <div class="input-group mb-2">
                                            <div class="input-group-prepend">
                                                <div class="input-group-text">{{ __('Size') }}:{{ $sizes[$i] }}</div>
                                            </div>
                                            <input type="number" step="1" min="0" class="form-control" id="size" name="quantities[]" value="{{ $quantities[$i] }}" placeholder="{{ __('Quantity') }}" required>
                                        </div>
                                    </div>
                                @endfor
                            </div>
                        </div>
                        <div id="dynamic-sizes">
                        </div>
                        <div class="row">
                            <div class="form-group col-md-12">
                                <label for="note" class="col-form-label">{{ __('Finished Note') }}</label>
                                <textarea class="form-control @error('note') is-invalid @enderror" id="note" name="note" placeholder="{{ __('Finished Note') }}" >{{ $product->note }}</textarea>
                                @error('note')
                                <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                @enderror
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4 form-group mt-12">
                                <label for="finishDate">{{ __('Finished Date') }}<span class="required-field">*</span></label>
                                <input type="date" class="form-control @error('startDate') is-invalid @enderror" id="finishDate" name="finishDate"  value="{{$product->finished_date}}" required>
                                @error('finishDate')
                                <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                @enderror
                            </div>
                            <div class="col-md-4 form-group">
                                <label for="attchedPic" class="col-form-label">{{ __('Finished Image') }}</label>
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
                                    @if($product->finished_image)
                                        <img src="{{ $product->imagepath() }}" id="attached-preview-img" class="mt-3"/>
                                    @else
                                        <img src="{{ asset('img/placeholder.png') }}" id="attached-preview-img" class="mt-3"/>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-4 form-group">
                                <label for="status" class="col-form-label">{{ __('Status') }}</label>
                                <select class="form-control" id="status" name="status">
                                    <option value="1" {{ $product->isActive() ? 'selected' : '' }}>{{ __('Active') }}</option>
                                    <option value="0" {{ $product->isActive() ? '' : 'selected' }}>{{ __('Inactive') }}</option>
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
<script src="{{ asset('js/finished-product.js') }}"></script>
@endsection
