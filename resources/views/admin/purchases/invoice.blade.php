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
                <h1 class="m-0 text-dark">{{ __('Purchase Invoice') }}</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item">
                        <a href="{{ route('dashboard') }}">{{ __('Dashboard') }}</a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="{{ route('purchases.index') }}">{{ __('Purchases') }}</a>
                    </li>
                    <li class="breadcrumb-item active">{{ __('Purchase Invoice') }}</li>
                </ol>
            </div>
        </div>
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <div class="content">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="invoice p-3 mb-3">
                        <!-- title row -->
                        <div class="row">
                            <div class="col-12">
                                <h4>
                                    @if ($helper->getGeneralSettigns()->logo)
                                        <img src="{{ AppHelper::instance()->getGeneralSettigns()->logo }}">
                                    @else
                                        {{ $helper->getGeneralSettigns()->compnayName }}
                                    @endif
                                    <br />
                                    <small>
                                        {{ $helper->getGeneralSettigns()->compnayTagline ? $helper->getGeneralSettigns()->compnayTagline : '' }}
                                    </small>
                                    <small
                                        class="float-right">{{ date_format(date_create($purchase->purchase_date), 'd-M-Y') }}</small>
                                </h4>
                            </div>
                            <!-- /.col -->
                        </div>
                        <br />
                        <!-- info row -->
                        <div class="row invoice-info">
                            <div class="col-sm-6 invoice-col">
                                <h4>{{ __('Supplier Info') }}:</h4>
                                <address>
                                    <strong>{{ $purchase->supplier->name }}</strong><br>
                                    {{ $purchase->supplier->email }}<br />
                                    {{ $purchase->supplier->phone_number }}<br />
                                    {{ $purchase->supplier->address }}
                                </address>
                            </div>
                            <!-- /.col -->

                            <!-- /.col -->
                            <div class="col-sm-6 invoice-col text-right">
                                <b>{{ __('PURCHASE CODE') }}: </b>
                                {{ $helper->pruchaseCode($purchase->purchase_code) }}<br>
                                <b>{{ __('Date') }}: </b>
                                {{ date_format(date_create($purchase->purchase_date), 'd-M-Y') }}<br>
                                <b>{{ __('Total') }}: </b>{{ $helper->formattedCurrency($purchase->total) }}<br>
                                <b>{{ __('Paid Amount') }}: </b>
                                {{ $helper->formattedCurrency($purchase->total_paid) }}<br />
                                <b>{{ __('Due Amount') }}: </b> {{ $helper->formattedCurrency($purchase->total_due) }}
                            </div>
                            <!-- /.col -->
                        </div>
                        <!-- /.row -->

                        <!-- Table row -->
                        <div class="row">
                            <div class="col-12 table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>{{ __('Product') }}</th>
                                            <th>{{ __('Purchased Qty') }}</th>
                                            <th>{{ __('Unit Price') }}</th>
                                            <th>{{ __('Discount') }}</th>
                                            <th class="text-right">{{ __('Total') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($purchase->purchaseProducts as $key => $product)
                                            <tr>
                                                <td>{{ ++$key }}</td>
                                                <td>{{ $product->product_name }}</td>
                                                <td>{{ $product->quantity }} {{ $product->unit }}</td>
                                                <td>{{ $helper->formattedCurrency($product->unit_price) }}</td>
                                                <td>{{ $helper->formattedCurrency($product->discountAmount()) }}
                                                    ({{ $product->discount > 0 ? $product->discount : 0 }}%)</td>
                                                <td class="text-right">{{ $helper->formattedCurrency($product->total) }}
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <!-- /.row -->

                        <div class="row mt-4">
                            <!-- accepted payments column -->
                            <div class="col-8">
                                <strong>{{ __('Payment Method') }}:</strong>
                                <p>{{ ucfirst($purchase->payment_type) }}</p>
                                <strong>{{ __('Status') }}:</strong>
                                <p>
                                    @if ($purchase->isActive())
                                        <span class="badge badge-success">{{ __('Active') }}</span>
                                    @else
                                        <span class="badge badge-warning">{{ __('Inactive') }}</span>
                                    @endif
                                </p>
                                <strong>{{ __('Payment Note') }}:</strong><br />
                                {{ $purchase->note }}
                            </div>
                            <!-- /.col -->
                            <div class="col-4">
                                <div class="table-responsive text-right">
                                    <table class="table">
                                        <tbody>
                                            <tr>
                                                <th>{{ __('Subtotal') }}:</th>
                                                <td>{{ $helper->formattedCurrency($purchase->sub_total) }}</td>
                                            </tr>
                                            <tr>
                                                <th>{{ __('Total Discount') }}:</th>
                                                <td>-{{ $helper->formattedCurrency($purchase->discount) }}</td>
                                            </tr>
                                            <tr>
                                                <th>{{ __('Trasnport') }}:</th>
                                                <td>+{{ $helper->formattedCurrency($purchase->trasnport) }}</td>
                                            </tr>
                                            <tr>
                                                <th>{{ __('Grand Total') }}:</th>
                                                <td>{{ $helper->formattedCurrency($purchase->total) }}</td>
                                            </tr>
                                            <tr>
                                                <th>{{ __('Total Paid') }}:</th>
                                                <td>{{ $helper->formattedCurrency($purchase->total_paid) }}</td>
                                            </tr>
                                            <tr>
                                                <th>{{ __('Total Due') }}:</th>
                                                <td>{{ $helper->formattedCurrency($purchase->total_due) }}</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <!-- /.col -->
                        </div>
                        <!-- /.row -->

                        <!-- this row will not appear when printing -->
                        <div class="row no-print">
                            <div class="col-12">
                                <a href="#" target="_blank" class="btn btn-secondary float-right print-btn"><i
                                        class="fas fa-print"></i> {{ __('Print') }}</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- /.content -->
@endsection
