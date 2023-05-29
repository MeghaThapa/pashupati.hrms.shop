@php
use App\Helpers\AppHelper;
$helper = AppHelper::instance();
@endphp
@extends('layouts.pdf')

@section('content-area')
    <h3>@lang('All Purchases')</h3>
    <table class="table-listing table table-bordered">
        thead>
        <tr>
            <th>@lang('#')</th>
            <th>{{ __('Purchase Code') }}</th>
            <th>{{ __('Date') }}</th>
            <th>{{ __('Supplier') }}</th>
            <th>{{ __('Subtotal') }}</th>
            <th>{{ __('Discount') }}</th>
            <th>{{ __('Trasnport') }}</th>
            <th>{{ __('Total') }}</th>
            <th>{{ __('Paid') }}</th>
            <th>{{ __('Due') }}</th>
            <th>{{ __('Status') }}</th>
        </tr>
        </thead>
        <tbody>
            @foreach ($purchases as $key => $purchase)
                <tr>
                    <td>{{ ++$key }}</td>
                    <td>
                        {{ $helper->pruchaseCode($purchase->purchase_code) }}
                    </td>
                    <td>
                        {{ \Carbon\Carbon::parse($purchase->purchase_date)->format('d-M-Y') }}
                    </td>
                    <td>{{ ucfirst($purchase->supplier->name) }} </td>
                    <td>{{ $helper->formattedCurrency($purchase->sub_total) }} </td>
                    <td>{{ $helper->formattedCurrency($purchase->discount) }}</td>
                    <td>{{ $helper->formattedCurrency($purchase->trasnport) }}</td>
                    <td>{{ $helper->formattedCurrency($purchase->total) }}</td>
                    <td>{{ $helper->formattedCurrency($purchase->total_paid) }}</td>
                    <td>{{ $helper->formattedCurrency($purchase->total_due) }}</td>
                    <td>
                        @if ($purchase->isActive())
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
