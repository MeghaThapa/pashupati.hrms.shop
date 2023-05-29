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
                <h1 class="m-0 text-dark">{{ __('Finished Products') }}</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item">
                        <a href="{{ route('dashboard') }}">{{ __('Dashboard') }}</a>
                    </li>
                    <li class="breadcrumb-item active">{{ __('Finished Products') }}</li>
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
                    <form action="{{ route('finished.index') }}" method="GET" role="search">
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
                        <a class="btn btn-secondary" href="{{ route('finished.pdf') }}">
                            <i class="fas fa-download"></i> @lang('Export')
                        </a>
                        <a href="{{ route('finished.create') }}" class="btn btn-primary">
                            {{ __('Add Finished') }} <i class="fas fa-plus-circle"></i>
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
                        <th>{{ __('Processing Code') }}</th>
                        <th>{{ __('Finished Code') }}</th>
                        <th>{{ __('Product Type') }}</th>
                        <th>{{ __('Sizes') }}</th>
                        <th>{{ __('Quantities') }}</th>
                        <th>{{ __('Finished Date') }}</th>
                        <th>{{ __('Status') }}</th>
                        <th class="text-right">{{ __('Action') }}</th>
                    </tr>
                    </thead>
                    <tbody>

                    @if ($products->total() > 0)
                        @foreach ($products as $key => $product)
                            <tr>
                                <td>{{ ++$key }}</td>
                                <td>
                                    <a href="{{ route('purchases.edit', $product->processingProduct->purchase->purchase_code) }}"
                                        title="{{ __('Edit purchase') }}">{{ $helper->pruchaseCode($product->processingProduct->purchase->purchase_code) }}</a>
                                </td>
                                <td>
                                    <a href="{{ route('processing.edit', $product->processingProduct->processing_code) }}"
                                        title="{{ __('Edit processing') }}">{{ $helper->processingCode($product->processingProduct->processing_code) }}</a>
                                </td>
                                <td>
                                    <a href="{{ route('finished.edit', $product->finished_code) }}"
                                        title="{{ __('Edit') }}">{{ $helper->finishedCode($product->finished_code) }}</a>
                                </td>
                                <td>{{ $product->productType->name }} </td>
                                <td>{{ $product->sizes }} </td>
                                <td>{{ $product->quantities }} </td>
                                <td>{{ date_format(date_create($product->finished_date), 'd-M-Y') }}</td>
                                <td>
                                    @if ($product->isActive())
                                        <span class="badge badge-success">{{ __('Active') }}</span>
                                    @else
                                        <span class="badge badge-warning">{{ __('Inactive') }}</span>
                                    @endif
                                </td>
                                <td class="text-right">
                                    <div class="btn-group">
                                        <button type="button"
                                                class="btn btn-secondary dropdown-toggle action-dropdown-toggle"
                                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <i class="fas fa-ellipsis-v"></i>
                                        </button>
                                        <div class="dropdown-menu dropdown-menu-right">
                                            @if ($product->isActive())
                                                <a href="{{ route('finished.status', $product->id) }}"
                                                    class="dropdown-item"><i class="fas fa-window-close"></i>
                                                    {{ __('Inactive') }}</a>
                                            @else
                                                <a href="{{ route('finished.status', $product->id) }}"
                                                    class="dropdown-item"><i class="fas fa-check-square"></i>
                                                    {{ __('Active') }}</a>
                                            @endif
                                            <a href="{{ route('finished.show', $product->slug) }}"
                                                class="dropdown-item"><i class="fas fa-eye"></i>
                                                {{ __('View') }}</a>
                                            <a href="{{ route('finished.edit', $product->slug) }}"
                                                class="dropdown-item"><i class="fas fa-edit"></i>
                                                {{ __('Edit') }}</a>
                                            <a href="{{ route('finished.delete', $product->slug) }}"
                                                class="dropdown-item delete-btn"
                                                data-msg="{{ __('Are you sure you want to delete this finished product?') }}"><i
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
                                    <p>{{ __('Sorry, no finished product found in the database. Create your very first finished product.') }}
                                    </p>
                                    <a href="{{ route('finished.create') }}" class="btn btn-primary">
                                        {{ __('Add Finished Product') }} <i class="fas fa-plus-circle"></i>
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
@endsection
