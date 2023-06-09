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
                <h1 class="m-0 text-dark">{{ __('Edit Storeout') }}</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item">
                        <a href="{{ route('dashboard') }}">{{ __('Dashboard') }}</a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="{{ route('storeout.index') }}">{{ __('Storeout') }}</a>
                    </li>
                    <li class="breadcrumb-item active">{{ __('Edit Storeout') }}</li>
                </ol>
            </div>
        </div>
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <div class="content">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">{{ __('Edit storeout') }}: {{ AppHelper::instance()->pruchaseCode($purchase->purchase_code) }}</h3>
                <div class="card-tools">
                    <a href="{{ route('storeout.index') }}" class="btn btn-block btn-primary">
                        <i class="fas fa-long-arrow-alt-left"></i> {{ __('Go Back') }}
                    </a>
                </div>
            </div>
            <!-- /.card-header -->
            <div class="card-body p-0">
                <form class="form-horizontal" action="{{ route('storeout.update', $purchase->purchase_code) }}" method="post" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-3 form-group">
                                <label for="srno" class="col-form-label">{{ __('SR No.') }}<span
                                        class="required-field">*</span></label>
                                <input type="text" class="form-control @error('srno') is-invalid @enderror"
                                       id="srno" name="srno" value="{{$purchase->sr_no}}"
                                       required>
                                @error('srno')
                                <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                @enderror
                            </div>
                            <div class="col-md-3 form-group">
                                <label for="billno" class="col-form-label">{{ __('Bill No.') }}</label>
                                <input type="text" class="form-control @error('billno') is-invalid @enderror"
                                       id="billno" name="billno" value="{{$purchase->bill_no}}">
                                @error('billno')
                                <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                @enderror
                            </div>
                            <div class="col-md-3 form-group">
                                <label for="ppno" class="col-form-label">{{ __('PP No.') }}</label>
                                <input type="text" class="form-control @error('ppno') is-invalid @enderror"
                                       id="ppno" name="ppno" value="{{$purchase->pp_no}}">
                                @error('ppno')
                                <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                @enderror
                            </div>
                            <div class="col-md-3 form-group">
                                <label for="infrom" class="col-form-label">{{ __('In From') }}<span
                                    class="required-field">*</span>
                                </label>
                                <select class="advance-select-box form-control @error('infrom') is-invalid @enderror" id="infrom" name="infrom"  required>
                                    <option value="" selected disabled>{{ __('Select a Infrom') }}</option>
                                    
                                        <option value="Import">Import</option>
                                        <option value="Local">Local</option>
                                        <option value="Sapt">Sapt</option>
                                    
                                </select>
                                @error('name')
                                <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                @enderror
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4 form-group">
                                <label for="purchaseDate" class="col-form-label">{{ __('Date') }}<span class="required-field">*</span></label>
                                <input type="date" class="form-control @error('purchaseDate') is-invalid @enderror" id="purchaseDate" name="purchaseDate"  value="{{$purchase->purchase_date}}" required>
                                @error('purchaseDate')
                                <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                @enderror
                            </div>
                            <div class="col-md-4 form-group">
                                <label for="type" class="col-form-label">{{ __('Type') }}<span class="required-field">*</span></label>
                                <select class="advance-select-box form-control @error('type') is-invalid @enderror" id="type" name="type" required data-placeholder="{{ __('Select a type') }}">
                                    @foreach ($storeintype as $storeintype)
                                        <option value="{{ $storeouttype->id }}" {{ $storeouttype->id == $purchase->storeout_id ? 'selected' : '' }}>{{ $storeouttype->name }}</option>
                                    @endforeach
                                </select>
                                @error('type')
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
                            <div class="col-md-4 form-group">
                                <label for="categoryName" class="col-form-label">{{ __('Category') }}<span
                                        class="required-field">*</span></label>
                                 <select class="advance-select-box form-control @error('categoryName') is-invalid @enderror" id="categoryName" name="categoryName"  required>
                                    <option value="" selected disabled>{{ __('Select a Product') }}</option>
                                    @foreach($categories as $key => $category)
                                        <option value="{{ $category->id }}" {{ $category->id == $purchase->category_id ? 'selected' : '' }}>{{ $category->name }}</option>
                                    @endforeach
                                </select>
                                @error('name')
                                <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                @enderror
                            </div>
                            <div class="col-md-4 form-group">
                                <label for="subcategoryName" class="col-form-label">{{ __('Subcategory') }}<span class="required-field">*</span></label>
                                <select class="advance-select-box form-control @error('subcategoryName') is-invalid @enderror" id="subcategoryName" name="subcategoryName"  required>
                                    <option value="" selected disabled>{{ __('Select a category') }}</option>
                                    @foreach($SubCategory as $key => $subcat)
                                        <option value="{{ $subcat->id }}" {{ $subcat->id == $purchase->subcategory_id ? 'selected' : '' }}>{{ $subcat->name }}</option>
                                    @endforeach
                                </select>
                                @error('name')
                                <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                @enderror
                            </div>
                            
                            <div class="col-md-4 form-group">
                                <label for="products" class="col-form-label">{{ __('Item Name') }}<span
                                        class="required-field">*</span>
                                </label>
                                 <select class="advance-select-box form-control @error('ProductName') is-invalid @enderror" id="ProductName" name="ProductName"  required>
                                    <option value="" selected disabled>{{ __('Select a Product') }}</option>
                                    @foreach($items as $key => $processpro)
                                        <option value="{{ $processpro->id }}" {{ $processpro->id == $purchase->items_id ? 'selected' : '' }}>{{ $processpro->item }}</option>
                                    @endforeach
                                </select>
                                @error('name')
                                <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                @enderror
                            </div>
                        </div>
                        <div class="row">
                            @foreach ($purchase->purchaseProducts as $key => $product)
                                <!--<div class="col-md-3 form-group">-->
                                <!--    <label for="products" class="col-form-label">{{ __('Product Name') }}<span class="required-field">*</span></label>-->
                                <!--    <input type="text" class="form-control @error('products.*') is-invalid @enderror" id="products" name="products[]" placeholder="{{ __('Product Name') }}" value="{{ $product->product_name }}"  required>-->
                                <!--    @error('products.*')-->
                                <!--    <span class="invalid-feedback" role="alert">-->
                                <!--                <strong>{{ $message }}</strong>-->
                                <!--            </span>-->
                                <!--    @enderror-->
                                <!--</div>-->
                                
                                
                                <div class="col-md-3 form-group">
                                    <div class="row">
                                        
                                        <div class="col-md-6">
                                            <label for="qunatities" class="col-form-label">{{ __('Quantity') }}<span class="required-field">*</span></label>
                                            <input type="number" class="form-control @error('qunatities') is-invalid @enderror edit-calculator" id="qunatities-{{ ++$key }}" name="quantities[]" placeholder="{{ __('Quantity') }}" data-number="{{ $key }}" min="1" value="{{ $product->quantity }}" required>
                                            @error('qunatities')
                                            <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                            @enderror
                                        </div>
                                        <div class="col-md-6">
                                            <label for="units" class="col-form-label">{{ __('Unit') }}<span class="required-field">*</span></label>
                                            <select class="form-control @error('units') is-invalid @enderror" name="units[]" id="units" required>
                                                @foreach($units as $unit)
                                                    <option value="{{ $unit->code }}" {{ $product->unit == $unit->code ? 'selected' : '' }}>{{ $unit->name }}</option>
                                                @endforeach
                                            </select>
                                            @error('units')
                                            <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-3 form-group">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <label for="unitPrices[]" class="col-form-label">{{ __('Unit Price') }}<span class="required-field">*</span></label>
                                            <input type="number" class="form-control @error('unitPrices') is-invalid @enderror edit-calculator" id="unitPrices-{{ $key }}" name="unitPrices[]" placeholder="{{ __('Unit Price') }}" data-number="{{ $key }}" min="1" value="{{ $product->unit_price }}" required>
                                            @error('unitPrices')
                                            <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                            @enderror
                                        </div>

                                        <div class="col-md-6">
                                            <label for="discounts[]" class="col-form-label">{{ __('Discount') }}<span class="required-field">*</span></label>
                                            <input type="number" step="any" min="0" class="form-control @error('discounts') is-invalid @enderror edit-calculator" id="discounts-{{ $key }}" data-number="{{ $key }}" name="discounts[]" placeholder="{{ __('Discount') }}"  value="{{ $product->discount }}" >
                                            @error('discounts')
                                            <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-{{ $key == count($purchase->purchaseProducts) ? 2: 3 }} form-group">
                                    <label for="singleTotal[]" class="col-form-label">{{ __('Product Total') }}</label>
                                    <input type="number" class="form-control @error('singleTotal') is-invalid @enderror" id="singleTotal-{{ $key }}" name="singleTotal[]" placeholder="{{ __('Product Total') }}" value="{{ $product->total }}"  readonly>
                                </div>
                                @if($key == count($purchase->purchaseProducts))
                                    <a href="javascript:void(0);" class="col-md-1 add_button btn btn-primary dynamic-btn" title="Add More"><i class="fas fa-plus"></i></a>
                                @endif
                            @endforeach
                        </div>
                        <div class="row">
                            <div class="field_wrapper col-md-12">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-3 form-group">
                                <label for="subTotal" class="col-form-label">{{ __('Sub Total') }}</label>
                                <input type="text" class="form-control @error('subTotal') is-invalid @enderror" id="subTotal" name="subTotal"  placeholder="{{ __('Sub Total') }}" value="{{ $purchase->sub_total }}" readonly>
                                @error('subTotal')
                                <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                @enderror
                            </div>
                            <div class="col-md-3 form-group">
                                <label for="totalDiscount" class="col-form-label">{{ __('Total Discount') }}</label>
                                <input type="number" step="any" min="0" class="form-control @error('totalDiscount') is-invalid @enderror" id="totalDiscount" name="totalDiscount" placeholder="{{ __('Total Discount') }}" readonly value="{{ $purchase->discount }}">
                                @error('totalDiscount')
                                <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                @enderror
                            </div>
                            <div class="col-md-3 form-group">
                                <label for="transportCost" class="col-form-label">{{ __('Freight Cost') }}</label>
                                <input type="number" class="form-control @error('transportCost') is-invalid @enderror" id="transportCost" name="transportCost" placeholder="{{ __('Freight Cost') }}" value="{{ $purchase->trasnport }}">
                                @error('transportCost')
                                <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                @enderror
                            </div>

                            <div class="col-md-3 form-group">
                                <label for="total" class="col-form-label">{{ __('Grand Total') }}</label>
                                <input type="number" class="form-control @error('total') is-invalid @enderror" id="total" name="total"  placeholder="{{ __('Grand Total') }}" value="{{ $purchase->total }}"  readonly>
                                @error('total')
                                <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                @enderror
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-3 form-group">
                                <label for="size" class="col-form-label">{{ __('Size') }}<span
                                    class="required-field">*</span>
                                </label>
                                 <select class="advance-select-box form-control @error('size') is-invalid @enderror" id="size" name="size[]"  required>
                                    <option value="" selected disabled>{{ __('Select a Size ') }}</option>
                                    @foreach($sizes as $key => $size)
                                        <option value="{{ $size->id }}" {{ $size->id == $purchase->size_id ? 'selected' : '' }}>{{ $size->name }}</option>
                                    @endforeach
                                </select>
                                @error('name')
                                <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                @enderror
                            </div>
                            <div class="col-md-3 form-group">
                                <label for="tax" class="col-form-label">{{ __('Tax Type') }}
                                </label>
                                 <select class="advance-select-box form-control @error('tax') is-invalid @enderror" id="tax" name="tax">
                                    <option value="" selected disabled>{{ __('Select a tax ') }}</option>
                                    
                                </select>
                                @error('name')
                                <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                @enderror
                            </div>
                            <div class="col-md-3 form-group">
                                <label for="totalPayment" class="col-form-label">{{ __('Total Payment') }}<span class="required-field">*</span></label>
                                <input type="number" class="form-control @error('totalPayment') is-invalid @enderror" id="totalPayment" name="totalPayment" value="{{ $purchase->total_paid }}"  placeholder="{{ __('Total Payment') }}">
                                @error('totalPayment')
                                <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                @enderror
                            </div>
                            <div class="col-md-3 form-group">
                                <label for="paymentType" class="col-form-label">{{ __('Payment Type') }}</label>
                                <select class="form-control" id="paymentType" name="paymentType">
                                    <option value="" selected disabled>{{ __('Select a payment method') }}</option>
                                    @foreach ($methods as $method)
                                        <option value="{{ $method->code }}" {{ $purchase->payment_type == $method->code ? 'selected' : '' }}>{{ $method->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-md-12">
                                <label for="note" class="col-form-label">{{ __('Purchase Note') }}</label>
                                <textarea class="form-control @error('note') is-invalid @enderror" id="note" name="note" placeholder="{{ __('Purchase Note') }}">{{ $purchase->note }}</textarea>
                                @error('note')
                                <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                @enderror
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-md-6">
                                <label for="purchaseImage" class="col-form-label">{{ __('Purchase Image') }}</label>
                                <div class="custom-file">
                                    <input type="file" class="custom-file-input @error('purchaseImage') is-invalid @enderror" id="attached-image" name="purchaseImage">
                                    <label class="custom-file-label" for="customFile">{{ __('Choose file') }}</label>
                                    @error('purchaseImage')
                                    <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                    @enderror
                                </div>
                                <div class="image-preview">
                                    @if($purchase->purchase_image)
                                        <img src="{{ $purchase->imagepath() }}" id="attached-preview-img" class="mt-3"/>
                                    @else
                                        <img src="{{ asset('img/placeholder.png') }}" id="attached-preview-img" class="mt-3"/>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-6 form-group">
                                <label for="status" class="col-form-label">{{ __('Status') }}</label>
                                <select class="form-control" id="status" name="status">
                                    <option value="1" {{ $purchase->isActive() ? 'selected' : '' }}>{{ __('Active') }}</option>
                                    <option value="0" {{ $purchase->isActive() ? '' : 'selected' }}>{{ __('Inactive') }}</option>
                                </select>
                            </div>
                        </div>
                        <input type="hidden" value="{{ count($purchase->purchaseProducts) }}" id="product-count" name="totalProducts">
                        <input type="hidden" name="unitInfo" id="unitInfo" data-names="{{ $unitNames }}" data-codes="{{ $unitCodes }}">
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
<script src="{{ asset('js/purchase.js') }}"></script>
@endsection

