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
                <h1 class="m-0 text-dark">{{ __('Transferred Products') }}</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item">
                        <a href="{{ route('dashboard') }}">{{ __('Dashboard') }}</a>
                    </li>
                    <li class="breadcrumb-item active">{{ __('Transferred Products') }}</li>
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
                    <form action="{{ route('transferred.index') }}" method="GET" role="search">
                        <div class="input-group">
                            <input type="text" name="term"
                                    placeholder="{{ __('Type finished code or product type finished date ...') }}"
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
                        <a class="btn btn-secondary" href="{{ route('transferred.pdf') }}">
                            <i class="fas fa-download"></i> @lang('Export')
                        </a>
                        <a href="{{ route('transferred.create') }}" class="btn btn-primary">
                            {{ __('Add Transferred') }} <i class="fas fa-plus-circle"></i>
                        </a>
                    </div>
                </div>
            </div>

            <div class="p-0 table-responsive table-custom my-3">
                <table class="table">
                    <thead>
                    <tr>
                        <th>@lang('#')</th>
                        <th>{{ __('Purchased Code') }}</th>
                        <th>{{ __('Processing Code') }}</th>
                        <th>{{ __('Finished Code') }}</th>
                        <th>{{ __('Transferred Code') }}</th>
                        <th>{{ __('Transferred Date') }}</th>
                        <th>{{ __('Showroom') }}</th>
                        <th>{{ __('Quantities') }}</th>
                        <th>{{ __('Cartoon Number') }}</th>
                        <th>{{ __('Status') }}</th>
                        <th>{{ __('Action') }}</th>
                    </tr>
                    </thead>
                    <tbody>

                    @if ($products->total() > 0)
                        @foreach ($products as $key => $product)
                            <tr>
                                <td>{{ ++$key }}</td>
                                <td>
                                    <a href="{{ route('purchases.edit', $product->finishedProduct->processingProduct->purchase->purchase_code) }}"
                                        title="{{ __('Edit purchase') }}">{{ $helper->pruchaseCode($product->finishedProduct->processingProduct->purchase->purchase_code) }}</a>
                                </td>
                                <td>
                                    <a href="{{ route('processing.edit', $product->finishedProduct->processingProduct->processing_code) }}"
                                        title="{{ __('Edit processing') }}">{{ $helper->processingCode($product->finishedProduct->processingProduct->processing_code) }}</a>
                                </td>
                                <td>
                                    <a href="{{ route('finished.edit', $product->finishedProduct->finished_code) }}"
                                        title="{{ __('Edit finished') }}">{{ $helper->finishedCode($product->finishedProduct->finished_code) }}</a>
                                </td>
                                <td>
                                    <a href="{{ route('transferred.edit', $product->transferred_code) }}"
                                        title="{{ __('Edit') }}">{{ $helper->transferredCode($product->transferred_code) }}</a>
                                </td>
                                <td>{{ date_format(date_create($product->transferred_date), 'd-M-Y') }}</td>
                                <td>{{ $product->showroom->name }} </td>
                                <td>
                                    {{ $product->finishedProduct->sizes }}<br />
                                    {{ $product->transferred_quantities }}
                                </td>
                                <td>{{ $product->cartoon_number }} </td>
                                <td>
                                    @if ($product->isActive())
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
                                            @if ($product->isActive())
                                                <a href="{{ route('transferred.status', $product->id) }}"
                                                    class="dropdown-item"><i class="fas fa-window-close"></i>
                                                    {{ __('Inactive') }}</a>
                                            @else
                                                <a href="{{ route('transferred.status', $product->id) }}"
                                                    class="dropdown-item"><i class="fas fa-check-square"></i>
                                                    {{ __('Active') }}</a>
                                            @endif
                                            <a href="{{ route('transferred.show', $product->id) }}"
                                                class="dropdown-item"><i class="fas fa-eye"></i>
                                                {{ __('View') }}</a>
                                            <a href="{{ route('transferred.edit', $product->id) }}"
                                                class="dropdown-item"><i class="fas fa-edit"></i>
                                                {{ __('Edit') }}</a>
                                            <a href="{{ route('transferred.delete', $product->id) }}"
                                                class="dropdown-item delete-btn"
                                                data-msg="{{ __('Are you sure you want to delete this product?') }}"><i
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
                                    <p>{{ __('Sorry, no transferred  product found in the database. Create your very first transferred product.') }}
                                    </p>
                                    <a href="{{ route('transferred.create') }}" class="btn btn-primary">
                                        {{ __('Add Transferred Product') }} <i class="fas fa-plus-circle"></i>
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
            {{ $products->links() }}
            <!-- pagination end -->
        </div>
    </div>
    <!-- /.content -->
@endsection
