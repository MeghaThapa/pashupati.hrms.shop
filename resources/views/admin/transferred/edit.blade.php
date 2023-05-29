@php
    use App\Helpers\AppHelper;
    $helper = AppHelper::instance();
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
                <h1 class="m-0 text-dark">{{ __('Edit Transferred Product') }}</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item">
                        <a href="{{ route('dashboard') }}">{{ __('Dashboard') }}</a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="{{ route('transferred.index') }}">{{ __('Transferred Products') }}</a>
                    </li>
                    <li class="breadcrumb-item active">{{ __('Edit Product') }}</li>
                </ol>
            </div>
        </div>
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <div class="content">
        <div class="container">
            <div class="row">
                <div class="card col-md-12">
                    <div class="card-header">
                        <h3 class="card-title">{{ __('Edit transferred product ') }}: {{ $helper->transferredCode($product->transferred_code) }}</h3>
                        <div class="card-tools">
                            <a href="{{ route('transferred.index') }}" class="btn btn-block btn-primary">
                                <i class="fas fa-long-arrow-alt-left"></i> {{ __('Go Back') }}
                            </a>
                        </div>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body p-0">
                        <form class="form-horizontal" action="{{ route('transferred.update', $product->id) }}" method="post" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            <div class="card-body">
                                {{--  <div class="row">
                                    <div class="col-md-6 form-group">
                                        <label for="purchaseProduct" class="col-form-label">{{ __('Purchased Product') }}<span class="required-field">*</span></label>
                                        <select class="advance-select-box purchase-select form-control @error('purchaseProduct') is-invalid @enderror" id="purchaseProduct" name="purchaseProduct" required>
                                            <option value="" selected disabled>{{ __('Select a purchase') }}</option>
                                            @foreach ($purchases as $purchase)
                                                @if($purchase->finishedProducts->contains('purchase_id', $purchase->id))
                                                    <option value="{{ $purchase->id }}" {{ $purchase->id == $product->purchase_id ? 'selected' : '' }}>{{ $helper->pruchaseCode($purchase->purchase_code) }}</option>
                                                @endif
                                            @endforeach
                                        </select>
                                        @error('purchaseProduct')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                    <div class="col-md-6 form-group">
                                        <label for="finishedProduct" class="col-form-label">{{ __('Finished Product') }}<span class="required-field">*</span></label>
                                        <select class="advance-select-box finished-select form-control @error('finishedProduct') is-invalid @enderror" id="finishedProduct" name="finishedProduct" required>
                                            <option value="" selected disabled>{{ __('Select a finished product by date') }}</option>
                                            @foreach ($product->purchase->finishedProducts as $finishedProduct)
                                                <option value="{{ $finishedProduct->id }}" {{ $finishedProduct->id  == $product->finished_id ? 'selected' : '' }}>Finished Date: {{ $finishedProduct->finished_date }}</option>
                                            @endforeach
                                        </select>
                                        @error('finishedProduct')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>  --}}
                                @php
                                    $sizes = explode(', ', $product->finishedProduct->sizes);
                                    $rmQuantities =  explode(', ', $product->finishedProduct->remaining_quantity);
                                    $finishedQuantites =  explode(', ', $product->finishedProduct->quantities);
                                    $transferredQuantites = explode(', ', $product->transferred_quantities);
                                @endphp

                                <div class="row" id="existing-columns">
                                    @foreach($sizes as $key => $size)
                                        <div class="col-md-3 form-group">
                                            <label for="productSizes" class="col-form-label">{{__('Product Size')}}</label>
                                            <input type="text" class="form-control" id="productSizes" name="productSizes[]" placeholder="{{__('Product Size')}}" value="{{ $size }}" readonly>
                                        </div>
                                        <div class="col-md-3 form-group">
                                            <label for="finishedProducts" class="col-form-label">{{__('Finished Quantity')}}</label>
                                            <input type="text" class="form-control" id="finishedProducts" name="finishedProducts[]" placeholder="{{__('Finished Quantity')}}" value="{{ $finishedQuantites[$key] }}" readonly>
                                        </div>
                                        <div class="col-md-3 form-group">
                                            <label for="remainingQuantities" class="col-form-label">{{__('Remaining Quantity')}}</label>
                                            <input type="text" class="form-control" id="remainingQuantities" name="remainingQuantities[]" placeholder="{{__('Remaining Quantity')}}" value="{{ $finishedQuantites[$key] - $transQuantities[$key] }}" readonly>
                                        </div>
                                        <div class="col-md-3 form-group">
                                            <label for="transferredQuantities" class="col-form-label">{{__('Transferred Quantity')}}<span class="required-field">*</span></label>
                                            <input type="number" min="0" step="any" class="form-control @error('transferredQuantities.*') is-invalid @enderror" id="transferredQuantities" name="transferredQuantities[]" placeholder="{{__('Transferred Quantity')}}" value="{{ $transferredQuantites[$key++] }}">
                                            @error('transferredQuantities.*')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{$message}}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    @endforeach
                                </div>
                                <div class="row" id="dynamic-sizes">
                                </div>
                                <div class="row">
                                    <div class="col-md-4 form-group">
                                        <label for="showroom" class="col-form-label">{{ __('Showroom') }}<span class="required-field">*</span></label>
                                        <select class="advance-select-box form-control @error('showroom.*') is-invalid @enderror" name="showroom" id="showroom" required>
                                            <option value="" selected disabled>{{ __('Select a showroom') }}</option>
                                            @foreach ($showrooms as $showroom)
                                                <option value="{{ $showroom->id }}" {{ $product->showroom_id == $showroom->id ? 'selected' : '' }}>{{ $showroom->name }}</option>
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
                                        <input type="text" class="form-control @error('cartoonNumbers') is-invalid @enderror" id="cartoonNumber" name="cartoonNumber" placeholder="{{ __('Cartoon/Box Number') }}" value="{{ $product->cartoon_number }}"  required>
                                        @error('cartoonNumber')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                    <div class="col-md-4 form-group mt-12">
                                        <label for="transferredDate">{{ __('Transferred Date') }}<span class="required-field">*</span></label>
                                        <input type="date" class="form-control @error('transferredDate') is-invalid @enderror" id="transferredDate" name="transferredDate"  value="{{$product->transferred_date}}" required>
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
                                        <textarea class="form-control @error('note') is-invalid @enderror" id="note" name="note" placeholder="{{ __('Transferred Note') }}">{{ $product->note }}</textarea>
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
                                            @if($product->transferred_image)
                                                <img src="{{ $product->imagepath() }}" id="attached-preview-img" class="mt-3"/>
                                            @else
                                                <img src="{{ asset('img/placeholder.png') }}" id="attached-preview-img" class="mt-3"/>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-6 form-group">
                                        <label for="status" class="col-form-label">{{ __('Status') }}</label>
                                        <select class="form-control" id="status" name="status">
                                            <option value="1" {{ $product->isActive() ? 'selected' : '' }}>{{ __('Active') }}</option>
                                            <option value="0"  {{ $product->isActive() ? '' : 'selected' }}>{{ __('Inactive') }}</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="row mt-2">
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
        </div>
    </div>
    <!-- /.content -->
@endsection

@section('extra-script')
<script src="{{ asset('js/select2/select2.min.js') }}"></script>
<script src="{{ asset('js/transferred-product.js') }}"></script>
@endsection
