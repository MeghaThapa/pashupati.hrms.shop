@php
use App\Helpers\AppHelper;
$helper = AppHelper::instance();
@endphp
@extends('layouts.admin')
@section('content')
    <!-- Content Header (Page header) -->
    <div class="content-header mb-4">
        <div class="row align-items-center">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">{{ __('Purchases') }}</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item">
                        <a href="{{ route('dashboard') }}">{{ __('Dashboard') }}</a>
                    </li>
                    <li class="breadcrumb-item active">{{ __('Purchases') }}</li>
                </ol>
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
                    <form action="{{ route('purchases.index') }}" method="GET" role="search">
                        <div class="input-group">
                            <input type="text" name="term"
                                    placeholder="{{ __('Type code or supplier or date ...') }}"
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
                        <a class="btn btn-secondary" href="{{ route('purchases.pdf') }}">
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
                        <th>{{ __('Purchase Code') }}</th>
                        <th>{{ __('Date') }}</th>
                        <th>{{ __('Supplier') }}</th>
                        <th>{{ __('Subtotal') }}</th>
                        <th>{{ __('Discount') }}</th>
                        <th>{{ __('Transport') }}</th>
                        <th>{{ __('Total') }}</th>
                        <th>{{ __('Paid') }}</th>
                        <th>{{ __('Due') }}</th>
                        <th>{{ __('Status') }}</th>
                        <th>{{ __('Action') }}</th>
                    </tr>
                    </thead>
                    <tbody>

                    @if ($purchases->total() > 0)
                        @foreach ($purchases as $key => $purchase)
                            <tr>
                                <td>{{ ++$key }}</td>
                                <td>
                                    <a
                                        href="{{ route('purchases.edit', $purchase->purchase_code) }}">{{ $helper->pruchaseCode($purchase->purchase_code) }}</a>
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
                                <td>
                                    <div class="btn-group">
                                        <button type="button"
                                                class="btn btn-secondary dropdown-toggle action-dropdown-toggle"
                                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <i class="fas fa-ellipsis-v"></i>
                                        </button>
                                        <div class="dropdown-menu dropdown-menu-right">
                                            @if ($purchase->isActive())
                                                <a href="{{ route('purchases.status', $purchase->purchase_code) }}"
                                                    class="dropdown-item"><i class="fas fa-window-close"></i>
                                                    {{ __('Inactive') }}</a>
                                            @else
                                                <a href="{{ route('purchases.status', $purchase->purchase_code) }}"
                                                    class="dropdown-item"><i class="fas fa-check-square"></i>
                                                    {{ __('Active') }}</a>
                                            @endif
                                            <a href="{{ route('purchases.invoice', $purchase->purchase_code) }}"
                                                class="dropdown-item"><i class="fas fa-file-alt"></i>
                                                {{ __('Invoice') }}</a>
                                            <a href="{{ route('purchases.show', $purchase->purchase_code) }}"
                                                class="dropdown-item"><i class="fas fa-eye"></i>
                                                {{ __('View') }}</a>
                                            <a href="{{ route('purchases.edit', $purchase->purchase_code) }}"
                                                class="dropdown-item"><i class="fas fa-edit"></i>
                                                {{ __('Edit') }}</a>
                                            <a href="{{ route('purchases.delete', $purchase->purchase_code) }}"
                                                class="dropdown-item delete-btn"
                                                data-msg="{{ __('Are you sure you want to delete this purchase?') }}"><i
                                                    class="fas fa-trash"></i> {{ __('Delete') }}</a>
                                        </div>
                                    </div>
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
            {{ $purchases->links() }}
            <!-- pagination end -->
        </div>
    </div>
    <!-- /.content -->
@endsection
