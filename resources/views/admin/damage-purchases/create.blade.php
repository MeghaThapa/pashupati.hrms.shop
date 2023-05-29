@php
    use \App\Helpers\AppHelper;
@endphp

@extends('layouts.admin')

@section('extra-style')
<link href="{{ asset('css/select2/select2.min.css') }}" rel="stylesheet" />
<link href="{{ asset('css/select2/select2-bootstrap4.css') }}" rel="stylesheet" />
<style>
.c-mt-12{
    margin-top: 12px;
}
</style>
@endsection

@section('content')
    <!-- Content Header (Page header) -->
    <div class="content-header mb-4">
        <div class="row align-items-center">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">{{ __('Create Damage Purchase') }}</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item">
                        <a href="{{ route('dashboard') }}">{{ __('Dashboard') }}</a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="{{ route('purchaseDamage.index') }}">{{ __('Damage Purchases') }}</a>
                    </li>
                    <li class="breadcrumb-item active">{{ __('Create Damage Purchase') }}</li>
                </ol>
            </div>
        </div>
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <div class="content">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">{{ __('Add a new damage purchase') }}</h3>
                <div class="card-tools">
                    <a href="{{ route('purchaseDamage.index') }}" class="btn btn-block btn-primary">
                        <i class="fas fa-long-arrow-alt-left"></i> {{ __('Go Back') }}
                    </a>
                </div>
            </div>
            <!-- /.card-header -->
            <div class="card-body p-0">
                <form class="form-horizontal" action="{{ route('purchaseDamage.store') }}" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="card-body">
                        <div class="row">
                            <div class="form-group col-md-6 c-mt-12">
                                <label for="damageReason">{{ __('Damage Reason') }}<span class="required-field">*</span></label>
                                <input type="text" class="form-control @error('damageReason') is-invalid @enderror" id="damageReason" name="damageReason" placeholder="{{ __('Damage Reason') }}" value="{{ old('damageReason') }}" required>
                                @error('damageReason')
                                <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                @enderror
                            </div>
                            <div class="form-group col-md-6">
                                <label for="purchaseProduct" class="col-form-label">{{ __('Purchases') }}<span class="required-field">*</span></label>
                                <select class="advance-select-box form-control purchase-select @error('purchaseProduct') is-invalid @enderror" id="purchaseProduct" name="purchaseProduct" data-placeholder="{{ __('Select a purchase') }}" required>
                                    <option value="" selected disabled>{{ __('Select a purchase') }}</option>
                                    @foreach ($purchases as $purchase)
                                        @if($purchase->purchaseDamage()->count() == 0)
                                            <option value="{{ $purchase->id }}">{{ AppHelper::instance()->pruchaseCode($purchase->purchase_code) }}</option>
                                        @endif
                                    @endforeach
                                </select>
                                @error('purchaseProduct')
                                <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                @enderror
                            </div>
                        </div>
                        <div class="row mb-3" id="dynamic-products">
                        </div>
                        <div class="row">
                            <div class="form-group col-md-12">
                                <label for="note" class="col-form-label">{{ __('Damage Note') }}</label>
                                <textarea class="form-control @error('note') is-invalid @enderror" id="note" name="note" placeholder="{{ __('Damage Note') }}">{{ old('note') }}</textarea>
                                @error('note')
                                <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                @enderror
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4 form-group c-mt-12">
                                <label for="damageDate">{{ __('Damage Date') }}<span class="required-field">*</span></label>
                                <input type="date" class="form-control @error('returnDate') is-invalid @enderror" id="damageDate" name="damageDate"  value="{{old('damageDate')}}" required>
                                @error('damageDate')
                                <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                @enderror
                            </div>
                            <div class="col-md-4 form-group">
                                <label for="attchedPic" class="col-form-label">{{ __('Damage Product Image') }}</label>
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
                                    <img src="" id="attached-preview-img" class="mt-3"/>
                                </div>
                            </div>
                            <div class="col-md-4 form-group">
                                <label for="status" class="col-form-label">{{ __('Status') }}</label>
                                <select class="form-control" id="status" name="status">
                                    <option value="1">{{ __('Active') }}</option>
                                    <option value="0">{{ __('Inactive') }}</option>
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-10">
                                <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> {{ __('Save Damage') }}</button>
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
<script>
// get products for selected purchase
$(document).ready(function(){
    $(".purchase-select").on("change", function () {
        let purchase =  $("#purchaseProduct option:selected").val();
        $.ajax({
            type: "POST",
            dataType : 'json',
            url: location.origin+"/admin/purchase-products",
            data: {_token: $('meta[name="csrf-token"]').attr('content'), id: purchase}
        })
        .done(function(data){
            let inputHtml = '';
            for(let i  in data)
            {
                inputHtml += '<div class="col-md-3"> <label for="productName" class="col-form-label">Product Name</label> <input type="text" class="form-control" id="products-'+i+'" name="products[]" placeholder="Product Name" value="'+data[i].product_name+'" readonly></div><div class="col-md-2"> <label for="currentQuantites" class="col-form-label">Purchase Qty</label> <input type="text" class="form-control" id="currentQuantites-'+i+'" name="currentQuantites[]" placeholder="Purchase Quantity" value="'+data[i].quantity+ ' ' +data[i].unit+ '" readonly></div><div class="col-md-2"> <label for="availableQuantities" class="col-form-label">Available Uty</label> <input type="text" class="form-control" value="'+ data[i].available_qty + ' ' +data[i].unit+ '" readonly></div><div class="col-md-2"> <label for="unitPrices" class="col-form-label">Unit Price</label> <input type="text" class="form-control" value="'+ data[i].unit_price + '" readonly></div><div class="col-md-3"> <label for="damageQuantities" class="col-form-label">Damage Qty<span class="required-field">*</span></label> <input type="number" step="any" min="0" max="'+data[i].available_qty+'" class="form-control" id="damageQuantities-'+i+'" name="damageQuantities[]" placeholder="Damage Quantity" required></div>';
            }
            if($( ".existing-products" ).length  > 0)
            {
                $( ".existing-products" ).remove();
            }
            $('#dynamic-products').html(inputHtml);
        })
        .fail(function(){
            console.log('Ajax Failed')
        });
    });
});
</script>
@endsection
