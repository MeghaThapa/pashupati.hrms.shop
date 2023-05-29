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
                <h1 class="m-0 text-dark">{{ __('Create Purchase') }}</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item">
                        <a href="{{ route('dashboard') }}">{{ __('Dashboard') }}</a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="{{ route('purchases.index') }}">{{ __('Purchases') }}</a>
                    </li>
                    <li class="breadcrumb-item active">{{ __('Create Purchase') }}</li>
                </ol>
            </div>
        </div>
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <div class="content">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">{{ __('Add a new purchase') }}</h3>
                <div class="card-tools">
                    <a href="{{ route('purchases.index') }}" class="btn btn-block btn-primary">
                        <i class="fas fa-long-arrow-alt-left"></i> {{ __('Go Back') }}
                    </a>
                </div>
            </div>
            <!-- /.card-header -->
            <div class="card-body p-0">
                <form class="form-horizontal" action="{{ route('purchases.store') }}" method="post"
                      enctype="multipart/form-data">
                    @csrf
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 form-group mt-3">
                                <label for="purchaseDate">{{ __('Purchase Date') }}<span
                                        class="required-field">*</span></label>
                                <input type="date" class="form-control @error('purchaseDate') is-invalid @enderror"
                                       id="purchaseDate" name="purchaseDate" value="{{ old('purchaseDate') }}"
                                       required>
                                @error('purchaseDate')
                                <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                @enderror
                            </div>
                            <div class="col-md-6 form-group">
                                <label for="supplier" class="col-form-label">{{ __('Supplier') }}<span
                                        class="required-field">*</span></label>
                                <select
                                    class="advance-select-box form-control @error('supplier') is-invalid @enderror"
                                    id="supplier" name="supplier" required>
                                    <option value="" selected disabled>{{ __('Select a supplier') }}</option>
                                    @foreach ($suppliers as $supplier)
                                        <option value="{{ $supplier->id }}">{{ $supplier->name }}</option>
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
                            <div class="col-md-3 form-group">
                                <label for="products" class="col-form-label">{{ __('Product Name') }}<span
                                        class="required-field">*</span></label>
                                <input type="text" class="form-control @error('products') is-invalid @enderror"
                                       id="products" name="products[]" placeholder="{{ __('Product Name') }}"
                                       required>
                                @error('products')
                                <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                @enderror
                            </div>
                            <div class="col-md-3 form-group">
                                <div class="row">
                                    <div class="col-md-6">
                                        <label for="qunatities" class="col-form-label">{{ __('Quantity') }}<span
                                                class="required-field">*</span></label>
                                        <input type="number" step="any" min="0"
                                               class="form-control @error('qunatities') is-invalid @enderror calculator"
                                               id="qunatities-1" data-number="1" name="quantities[]"
                                               placeholder="{{ __('Quantity') }}" min="1" required>
                                        @error('qunatities')
                                        <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                        @enderror
                                    </div>

                                    <div class="col-md-6">
                                        <label for="units" class="col-form-label">{{ __('Unit') }}<span
                                                class="required-field">*</span></label>
                                        <select class="form-control @error('units') is-invalid @enderror"
                                                name="units[]" id="units" required>
                                            <option value="" disabled>{{ __('Select a unit') }}</option>
                                            @foreach ($units as $unit)
                                                <option value="{{ $unit->code }}">{{ $unit->name }}</option>
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
                                        <label for="unitPrices[]"
                                               class="col-form-label">{{ __('Unit Price') }}<span
                                                class="required-field">*</span></label>
                                        <input type="number" step="any" min="0"
                                               class="form-control @error('unitPrices') is-invalid @enderror calculator"
                                               id="unitPrices-1" data-number="1" name="unitPrices[]"
                                               placeholder="{{ __('Unit Price') }}" min="1" required>
                                        @error('unitPrices')
                                        <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                        @enderror
                                    </div>
                                    <div class="col-md-6">
                                        <label for="discounts[]"
                                               class="col-form-label">{{ __('Discount') }}(%)</label>
                                        <input type="number" step="any" min="0" max="99"
                                               class="form-control @error('discounts') is-invalid @enderror calculator"
                                               id="discounts-1" data-number="1" name="discounts[]"
                                               placeholder="{{ __('Discount') }}">
                                        @error('discounts')
                                        <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-2 form-group">
                                <label for="singleTotal[]"
                                       class="col-form-label">{{ __('Product Total') }}</label>
                                <input type="text" step="any" min="0"
                                       class="form-control @error('singleTotal') is-invalid @enderror"
                                       id="singleTotal-1" name="singleTotal[]"
                                       placeholder="{{ __('Product Total') }}" readonly>
                            </div>
                            <a href="javascript:void(0);" class="col-md-1 add_button btn btn-primary dynamic-btn"
                               title="Add More"><i class="fas fa-plus"></i></a>
                        </div>
                        <div class="row">
                            <div class="field_wrapper col-md-12">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-3 form-group">
                                <label for="subTotal" class="col-form-label">{{ __('Sub Total') }}</label>
                                <input type="number" step="any" min="0"
                                       class="form-control @error('subTotal') is-invalid @enderror" id="subTotal"
                                       name="subTotal" placeholder="{{ __('Sub Total') }}" readonly>
                                @error('subTotal')
                                <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                @enderror
                            </div>
                            <div class="col-md-3 form-group">
                                <label for="totalDiscount"
                                       class="col-form-label">{{ __('Total Discount') }}</label>
                                <input type="number" step="any" min="0"
                                       class="form-control @error('totalDiscount') is-invalid @enderror"
                                       id="totalDiscount" name="totalDiscount"
                                       placeholder="{{ __('Total Discount') }}" readonly>
                                @error('totalDiscount')
                                <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                @enderror
                            </div>
                            <div class="col-md-3 form-group">
                                <label for="transportCost"
                                       class="col-form-label">{{ __('Transport Cost') }}</label>
                                <input type="number" step="any" min="0"
                                       class="form-control @error('transportCost') is-invalid @enderror"
                                       id="transportCost" name="transportCost"
                                       placeholder="{{ __('Transport Cost') }}">
                                @error('transportCost')
                                <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                @enderror
                            </div>
                            <div class="col-md-3 form-group">
                                <label for="total" class="col-form-label">{{ __('Grand Total') }}</label>
                                <input type="number" step="any" min="0"
                                       class="form-control @error('total') is-invalid @enderror" id="total"
                                       name="total" placeholder="{{ __('Grand Total') }}" readonly>
                                @error('total')
                                <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                @enderror
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 form-group">
                                <label for="totalPayment" class="col-form-label">{{ __('Total Paid') }}<span
                                        class="required-field">*</span></label>
                                <input type="number" step="any" min="0"
                                       class="form-control @error('totalPayment') is-invalid @enderror"
                                       id="totalPayment" name="totalPayment" value="{{ old('totalPayment') }}"
                                       placeholder="{{ __('Total Payment') }}">
                                @error('totalPayment')
                                <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                @enderror
                            </div>
                            <div class="col-md-6 form-group">
                                <label for="paymentMethod"
                                       class="col-form-label">{{ __('Payment Method') }}</label>
                                <select class="advance-select-box form-control" id="paymentMethod"
                                        name="paymentMethod">
                                    <option value="" selected disabled>{{ __('Select a payment method') }}
                                    </option>
                                    @foreach ($methods as $method)
                                        <option value="{{ $method->code }}">{{ $method->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-md-12">
                                <label for="note" class="col-form-label">{{ __('Purchase Note') }}</label>
                                <textarea class="form-control @error('note') is-invalid @enderror" id="note"
                                          name="note"
                                          placeholder="{{ __('Purchase Note') }}">{{ old('note') }}</textarea>
                                @error('note')
                                <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                @enderror
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-md-6">
                                <label for="purchaseImage"
                                       class="col-form-label">{{ __('Purchase Image') }}</label>
                                <div class="custom-file">
                                    <input type="file"
                                           class="custom-file-input @error('purchaseImage') is-invalid @enderror"
                                           id="attached-image" name="purchaseImage">
                                    <label class="custom-file-label"
                                           for="customFile">{{ __('Choose file') }}</label>
                                    @error('purchaseImage')
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
                        <input type="hidden" name="unitInfo" id="unitInfo" data-names="{{ $unitNames }}"
                               data-codes="{{ $unitCodes }}">
                        <div class="row">
                            <div class="col-sm-10">
                                <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i>
                                    {{ __('Save Purchase') }}</button>
                            </div>
                        </div>
                    </div>
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
