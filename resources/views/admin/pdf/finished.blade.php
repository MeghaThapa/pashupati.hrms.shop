@php
use App\Helpers\AppHelper;
$helper = AppHelper::instance();
@endphp

@extends('layouts.pdf')

@section('content-area')
    <h3>@lang('All Finished Products')</h3>
    <table class="table-listing table table-bordered">
        <thead>
            <tr>
                <th>@lang('#')</th>
                <th>{{ __('Purchase Code') }}</th>
                <th>{{ __('Processing Code') }}</th>
                <th>{{ __('Finished Code') }}</th>
                <th>{{ __('Product Type') }}</th>
                <th>{{ __('Sizes') }}</th>
                <th>{{ __('Quantities') }}</th>
                <th>{{ __('Finished Date') }}</th>
                <th>{{ __('Status') }}</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($products as $key => $product)
                <tr>
                    <td>{{ ++$key }}</td>
                    <td>{{ $helper->pruchaseCode($product->processingProduct->purchase->purchase_code) }}</td>
                    <td>{{ $helper->processingCode($product->processingProduct->processing_code) }}</td>
                    <td>{{ $helper->finishedCode($product->finished_code) }}</td>
                    <td>{{ $product->productType->name }} </td>
                    <td>{{ $product->sizes }} </td>
                    <td>{{ $product->quantities }} </td>
                    <td>{{ date_format(date_create($product->finished_date), 'd-M-Y') }}</td>
                    <td>
                        @if ($product->isActive())
                            <span class="badge badge-success">{{ __('Active') }}</span>
                        @else
                            <span class="badge badge-warning">{{ __('Inactive') }}</span>
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection
