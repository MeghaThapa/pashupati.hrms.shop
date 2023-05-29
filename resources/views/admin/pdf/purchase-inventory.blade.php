@php
use App\Helpers\AppHelper;
$helper = AppHelper::instance();
@endphp

@extends('layouts.pdf')

@section('content-area')
    <h3>@lang('All Purchase Products')</h3>
    <table class="table-listing table table-bordered">
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
                    <td class="text-right">{{ $helper->pruchaseCode($product->purchase->purchase_code) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection
