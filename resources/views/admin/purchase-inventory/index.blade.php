@php
use App\Helpers\AppHelper;
$helper = AppHelper::instance();
@endphp

@extends('layouts.admin')

@section('content')
    <!-- Content Header (Page header) -->
    <div class="content-header mb-4">
        <div>
            <div class="row align-items-center">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark">{{ __('Purchase Inventory') }}</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item">
                            <a href="{{ route('dashboard') }}">{{ __('Dashboard') }}</a>
                        </li>
                        <li class="breadcrumb-item">
                            <a href="{{ route('purchases.index') }}">{{ __('Purchases') }}</a>
                        </li>
                        <li class="breadcrumb-item active">{{ __('Purchase Inventory') }}</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <div class="content">
        <div class="container-fluid">
            <div class="col-md-12">
                @include('admin.includes.alert')
            </div>
            <div class="row">
                <div class="col-lg-3 col-md-5 col-6 mb-2">
                    <form action="{{ route('purchaseInventory.index') }}" method="GET" role="search">
                        <div class="input-group">
                            <input type="text" name="term"
                                    placeholder="{{ __('Type name or quantity or return quantity or damage quantity ...') }}"
                                    class="form-control" autocomplete="off"
                                    value="{{ request('term') ? request('term') : '' }}" required>
                            <span class="input-group-append">
                                    <button type="submit" class="btn btn-primary">{{ __('Search') }}</button>
                                </span>
                        </div>
                    </form>
                </div>
                <div class="col-lg-9 col-md-7 col-6">
                    <div class="card-tools text-md-right">
                        <a class="btn btn-secondary" href="{{ route('purchaseInventory.pdf') }}">
                            <i class="fas fa-download"></i> @lang('Export')
                        </a>
                        <a href="{{ route('purchases.create') }}" class="btn btn-primary">
                            {{ __('Add Purchase') }} <i class="fas fa-plus-circle"></i>
                        </a>
                    </div>
                </div>
            </div>
            <div class="p-0 table-responsive table-custom my-3">
                <table class="table">
                    <thead>
                    <tr>
                        <th>@lang('#')</th>
                        <th>{{ __('Product') }}</th>
                        <th>{{ __('Purchased Qty') }}</th>
                        <th>{{ __('Used Qty') }}</th>
                        <th>{{ __('Return Qty') }}</th>
                        <th>{{ __('Damage Qty') }}</th>
                        <th>{{ __('Available Qty') }}</th>
                        <th>{{ __('Unit Price') }}</th>
                        <th class="text-right">{{ __('Purchase') }}</th>
                    </tr>
                    </thead>
                    <tbody>

                    @if ($products->total() > 0)
                        @foreach ($products as $key => $product)
                            @php
                                $returnQty = $product->return_quantity > 0 ? $product->return_quantity : 0;
                                $damageQty = $product->damage_quantity > 0 ? $product->damage_quantity : 0;
                            @endphp
                            <tr>
                                <td>{{ ++$key }}</td>
                                <td>{{ $product->product_name }}</td>
                                <td>{{ $product->quantity }} {{ $product->unit }}</td>
                                <td>{{ $product->usedQty() }} {{ $product->unit }}</td>
                                <td>{{ $returnQty }} {{ $product->unit }}</td>
                                <td>{{ $damageQty }} {{ $product->unit }}</td>
                                <td>{{ $product->availableQuantity() }} {{ $product->unit }}</td>
                                <td>{{ $helper->formattedCurrency($product->unit_price) }}</td>
                                <td class="text-right">
                                    <a
                                        href="{{ route('purchases.show', $product->purchase->purchase_code) }}">{{ $helper->pruchaseCode($product->purchase->purchase_code) }}</a>
                                </td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="10">
                                <div class="data_empty">
                                    <img src="{{ asset('img/result-not-found.svg') }}" alt="" title="">
                                    <p>{{ __('Sorry, no purchase found in the database. Create your very first purchase.') }}</p>
                                    <a href="{{ route('purchases.create') }}" class="btn btn-primary">
                                        {{ __('Add Purchase') }} <i class="fas fa-plus-circle"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endif
                    </tbody>
                </table>
            </div>
            <!-- /.card-body -->

            <!-- pagination start -->
            {{ $products->links() }}
            <!-- pagination end -->
        </div>
    </div>
    <!-- /.content -->
@endsection
