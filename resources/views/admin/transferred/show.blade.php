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
                <h1 class="m-0 text-dark">{{ __('View Transferred Product') }}</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item">
                        <a href="{{ route('dashboard') }}">{{ __('Dashboard') }}</a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="{{ route('transferred.index') }}">{{ __('Transferred Products') }}</a>
                    </li>
                    <li class="breadcrumb-item active">{{ __('View Product') }}</li>
                </ol>
            </div>
        </div>
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <div class="content">
        <div class="container">
            <div class="row">
                <div class="card col-md-12">
                    <div class="card-header">
                        <h3 class="card-title">{{ __('View transferred product from finished product') }}:
                            {{ $helper->finishedCode($product->finishedProduct->finished_code) }}</h3>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body p-0">
                        <div class="row">
                            <div class="col-md-12 col-lg-6 text-center justify-content-center align-self-center">
                                @if ($product->transferred_image)
                                    <img src="{{ $product->imagepath() }}" alt="{{ __('Finished Product Image') }}"
                                        class="img-fluid" class="mt-3" />
                                @else
                                    <img src="{{ asset('img/placeholder.png') }}"
                                        alt="{{ __('Finished Product Image') }}" class="img-fluid" class="mt-3" />
                                @endif
                            </div>
                            <div class="col-md-12 col-lg-6 table-responsive view-table">
                                <table class="table">
                                    <tbody>
                                        <tr>
                                            <td>
                                                <strong>{{ __('Purchase Code') }}:</strong>
                                                <a href="{{ route('purchases.edit', $product->finishedProduct->processingProduct->purchase->purchase_code) }}"
                                                    title="{{ __('Edit purchase') }}">{{ $helper->pruchaseCode($product->finishedProduct->processingProduct->purchase->purchase_code) }}</a>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <strong>{{ __('Processing Code') }}:</strong>
                                                <a href="{{ route('processing.edit', $product->finishedProduct->processingProduct->processing_code) }}"
                                                    title="{{ __('Edit processing') }}">{{ $helper->processingCode($product->finishedProduct->processingProduct->processing_code) }}</a>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <strong>{{ __('Finished Code') }}:</strong>
                                                <a href="{{ route('finished.edit', $product->finishedProduct->finished_code) }}"
                                                    title="{{ __('Edit finished') }}">{{ $helper->finishedCode($product->finishedProduct->finished_code) }}</a>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <strong>{{ __('Transferred Code') }}:</strong>
                                                <a href="{{ route('transferred.edit', $product->transferred_code) }}"
                                                    title="{{ __('Edit') }}">{{ $helper->transferredCode($product->transferred_code) }}</a>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <strong>{{ __('Finished  Date') }}:</strong>
                                                {{ date_format(date_create($product->finishedProduct->finished_date), 'd-M-Y') }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <strong>{{ __('Transferred Date') }}:</strong>
                                                {{ date_format(date_create($product->transferred_date), 'd-M-Y') }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <strong>{{ __('Showroom') }}:</strong>
                                                {{ $product->showroom->name }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <strong>{{ __('Cartoon Number') }}:</strong>
                                                {{ $product->cartoon_number }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><strong>{{ __('Transferred Details:') }}</strong>
                                                <table>
                                                    <thead>
                                                        <tr>
                                                            <th>{{ __('Size') }}</th>
                                                            <th>{{ __('Finished Qty') }}</th>
                                                            <th>{{ __('Transferred Qty') }}</th>
                                                            <th class="text-right">{{ __('Remaining Qty') }}</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach ($sizes as $key => $size)
                                                            <tr>
                                                                <td>{{ $size }}</td>
                                                                <td>{{ $quantities[$key] }}</td>
                                                                <td>{{ $transQuantities[$key] }}</td>
                                                                <td class="text-right">
                                                                    {{ $quantities[$key] - $rmQuantites[$key++] }}</td>
                                                            </tr>
                                                        @endforeach

                                                    </tbody>
                                                </table>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><strong>{{ __('Note') }}:</strong> {{ $product->note }}</td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <strong>{{ __('Status') }}:</strong>
                                                @if ($product->isActive())
                                                    <span class="badge badge-success">{{ __('Active') }}</span>
                                                @else
                                                    <span class="badge badge-warning">{{ __('Inactive') }}</span>
                                                @endif
                                            </td>
                                        </tr>

                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer no-print">
                        <a href="{{ route('transferred.index') }}" class="btn btn-primary">
                            <i class="fas fa-long-arrow-alt-left"></i> {{ __('Go Back') }}
                        </a>
                        <a href="#" class="btn btn-secondary float-right print-btn"><i class="fas fa-print"></i>
                            {{ __('Print') }}</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- /.content -->

@endsection
