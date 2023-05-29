@php
use App\Helpers\AppHelper;
$helper = AppHelper::instance();
@endphp
@extends('layouts.pdf')

@section('content-area')
    <h3>@lang('All Return Purchases')</h3>
    <table class="table-listing table table-bordered">
        <thead>
            <tr>
                <th>@lang('#')</th>
                <th>{{ __('Purchased Code') }}</th>
                <th>{{ __('Return Reason') }}</th>
                <th>{{ __('Refund Amount') }}</th>
                <th>{{ __('Return Date') }}</th>
                <th>{{ __('Note') }}</th>
                <th>{{ __('Status') }}</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($purchaseReturns as $key => $return)
                <tr>
                    <td>{{ ++$key }}</td>
                    <td>{{ $helper->pruchaseCode($return->purchase->purchase_code) }}
                    </td>
                    <td>{{ $return->return_reason }} </td>
                    <td>{{ $helper->formattedCurrency($return->refund_amount) }}
                    </td>
                    <td>{{ \Carbon\Carbon::parse($return->return_date)->format('d-M-Y') }}</td>
                    <td>{{ $return->shortNote() }} </td>
                    <td>
                        @if ($return->isActive())
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
