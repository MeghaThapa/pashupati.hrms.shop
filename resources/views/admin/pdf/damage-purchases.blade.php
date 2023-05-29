@php
use App\Helpers\AppHelper;
$helper = AppHelper::instance();
@endphp
@extends('layouts.pdf')

@section('content-area')
    <h3>@lang('All Damage Purchases')</h3>
    <table class="table-listing table table-bordered">
        <thead>
            <tr>
                <th>@lang('#')</th>
                <th>{{ __('Purchased Code') }}</th>
                <th>{{ __('Damage Reason') }}</th>
                <th>{{ __('Damage Date') }}</th>
                <th>{{ __('Note') }}</th>
                <th>{{ __('Status') }}</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($damagePruchases as $key => $damagePurchase)
                <tr>
                    <td>{{ ++$key }}</td>
                    <td>{{ $helper->pruchaseCode($damagePurchase->purchase->purchase_code) }}
                    </td>
                    <td>{{ $damagePurchase->damage_reason }} </td>
                    <td>{{ \Carbon\Carbon::parse($damagePurchase->damage_date)->format('d-M-Y') }}
                    </td>
                    <td>{{ $damagePurchase->shortNote() }} </td>
                    <td>
                        @if ($damagePurchase->isActive())
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
