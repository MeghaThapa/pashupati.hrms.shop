@php
use App\Helpers\AppHelper;
$helper = AppHelper::instance();
@endphp

@extends('layouts.pdf')

@section('content-area')
    <h3>@lang('All Expenses')</h3>
    <table class="table-listing table table-bordered">
        <thead>
            <tr>
                <th>@lang('#')</th>
                <th>{{ __('Reason') }}</th>
                <th>{{ __('Expense Category') }}</th>
                <th>{{ __('Amount') }}</th>
                <th>{{ __('Date') }}</th>
                <th>{{ __('Note') }}</th>
                <th>{{ __('Status') }}</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($expenses as $key => $expense)
                <tr>
                    <td>{{ ++$key }}</td>
                    <td>{{ $expense->expense_reason }}</td>
                    <td>{{ $expense->expenseCategory->name }} </td>
                    <td>{{ $helper->formattedCurrency($expense->amount) }}</td>
                    <td>{{ \Carbon\Carbon::parse($expense->expense_date)->format('d-M-Y') }}</td>
                    <td>{{ $expense->shortNote() }} </td>
                    <td>
                        @if ($expense->isActive())
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
