@php
use App\Helpers\AppHelper;
$helper = AppHelper::instance();
@endphp

@extends('layouts.pdf')

@section('content-area')
    <h3>@lang('All Transferred Products')</h3>
    <table class="table-listing table table-bordered">
        <thead>
            <tr>
                <th>@lang('#')</th>
                <th>{{ __('Purchased Code') }}</th>
                <th>{{ __('Finished Code') }}</th>
                <th>{{ __('Transferred Code') }}</th>
                <th>{{ __('Transferred Date') }}</th>
                <th>{{ __('Showroom') }}</th>
                <th>{{ __('Quantities') }}</th>
                <th>{{ __('Status') }}</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($products as $key => $product)
                <tr>
                    <td>{{ ++$key }}</td>
                    <td>{{ $helper->pruchaseCode($product->finishedProduct->processingProduct->purchase->purchase_code) }}
                    </td>
                    <td>{{ $helper->finishedCode($product->finishedProduct->finished_code) }}</td>
                    <td>{{ $helper->transferredCode($product->transferred_code) }}</td>
                    <td>{{ date_format(date_create($product->transferred_date), 'd-M-Y') }}</td>
                    <td>{{ $product->showroom->name }} </td>
                    <td>
                        {{ $product->finishedProduct->sizes }}<br />
                        {{ $product->transferred_quantities }}
                    </td>
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
