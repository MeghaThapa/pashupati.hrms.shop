@php
use App\Helpers\AppHelper;
$helper = AppHelper::instance();
@endphp
@extends('layouts.pdf')

@section('content-area')
    <h3>@lang('All Processing Products')</h3>
    <table class="table-listing table table-bordered">
        <thead>
            <tr>
                <th>@lang('#')</th>
                <th>{{ __('Purchased Code') }}</th>
                <th>{{ __('Processing Code') }}</th>
                @if (count($steps) <= 6)
                    @foreach ($steps as $step) <th>{{ $step->step_name }}</th> @endforeach
                @endif
                <th>{{ __('Start Date') }}</th>
                <th>{{ __('End Date') }}</th>
                <th>{{ __('Status') }}</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($processingProducts as $key => $product)
                <tr>
                    <td>{{ ++$key }}</td>
                    <td>{{ $helper->pruchaseCode($product->purchase->purchase_code) }}</td>
                    <td>{{ $helper->instance()->processingCode($product->processing_code) }}</td>

                    @if (count($steps) <= 6)
                        @foreach ($steps as $step) <td>
                        @foreach ($product->selectedStaff()->where('processing_step_id', $step->step_id)->get()
        as $staff)
                            {{ $staff->name }}, @endforeach
                            </td>
                        @endforeach
                    @endif
                    <td>{{ date_format(date_create($product->start_date), 'd-M-Y') }}</td>
                    <td>{{ date_format(date_create($product->end_date), 'd-M-Y') }}</td>
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
