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
                <h1 class="m-0 text-dark">{{ __('View Supplier') }}</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item">
                        <a href="{{ route('dashboard') }}">{{ __('Dashboard') }}</a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="{{ route('suppliers.index') }}">{{ __('Suppliers') }}</a>
                    </li>
                    <li class="breadcrumb-item active">{{ __('View supplier') }}</li>
                </ol>
            </div>
        </div>
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <div class="content">
        <div class="container">
            @if (count($purchases) > 0)
                <div class="row">
                    <div class="col-12 col-sm-6 col-md-4 stats">
                        <a href="{{ route('purchases.index') }}?term={{ $supplier->name }}" class="info-box">
                            <span class="info-box-icon bg-success brand-color elevation-1"><i
                                    class="fas fa-shopping-basket"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">{{ __('Purchases Count') }}</span>
                                <span class="info-box-number"> {{ $purchases->count() }} </span>
                            </div>
                        </a>
                    </div>
                    <div class="col-12 col-sm-6 col-md-4 stats">
                        <div class="info-box">
                            <span class="info-box-icon bg-success brand-color elevation-1"><i
                                    class="fas fa-wallet"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">{{ __('Purchases Amount') }}</span>
                                <span
                                    class="info-box-number">{{ $helper->formattedCurrency($purchases->sum('total')) }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-sm-6 col-md-4 stats">
                        <div class="info-box">
                            <span class="info-box-icon bg-success brand-color elevation-1"><i
                                    class="fas fa-money-check-alt"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">{{ __('Total Paid') }}</span>
                                <span
                                    class="info-box-number">{{ $helper->formattedCurrency($purchases->sum('total_paid')) }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-sm-6 col-md-4 stats">
                        <div class="info-box">
                            <span class="info-box-icon bg-success brand-color elevation-1"><i
                                    class="fas fa-file-invoice-dollar"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">{{ __('Total Due') }}</span>
                                <span
                                    class="info-box-number">{{ $helper->formattedCurrency($purchases->sum('total') - $purchases->sum('total_paid')) }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-sm-6 col-md-4 stats">
                        <div class="info-box">
                            <span class="info-box-icon bg-success brand-color elevation-1"><i
                                    class="fas fa-percentage"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">{{ __('Total Discount') }}</span>
                                <span class="info-box-number">{{ $helper->formattedCurrency($totalDiscount) }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-sm-6 col-md-4 stats">
                        <div class="info-box">
                            <span class="info-box-icon bg-success brand-color elevation-1"><i
                                    class="fas fa-dolly"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">{{ __('Total Transport') }}</span>
                                <span
                                    class="info-box-number">{{ $helper->formattedCurrency($purchases->sum('trasnport')) }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
            <div class="row">
                <div class="card col-md-12">
                    <div class="card-header">
                        <h3 class="card-title">{{ __('View supplier') }}: {{ $supplier->name }}</h3>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body p-0">
                        <div class="row">
                            <div class="col-md-12 col-lg-4 text-center justify-content-center align-self-center">
                                @if (!empty($supplier->profile_picture))
                                    <img src="{{ $supplier->profilePic() }}" class="img-fluid">
                                @else
                                    <div class="show-no-preview"></div>
                                @endif
                            </div>
                            <div class="col-md-12 col-lg-8 table-responsive view-table">
                                <table class="table">
                                    <tbody>
                                        <tr>
                                            <td><strong>{{ __('Name') }}:</strong> {{ $supplier->name }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>{{ __('Email') }}:</strong> {{ $supplier->email }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>{{ __('Phone') }}:</strong> {{ $supplier->phone_number }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>{{ __('Company Name') }}:</strong>
                                                {{ $supplier->company_name }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>{{ __('Designation') }}:</strong> {{ $supplier->designation }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><strong>{{ __('Address') }}:</strong> {{ $supplier->address }}</td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <strong>{{ __('Status') }}:</strong>
                                                @if ($supplier->isActive())
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
                        <a href="{{ route('suppliers.index') }}" class="btn btn-primary">
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
