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
                <h1 class="m-0 text-dark">{{ __('Processing Products') }}</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item">
                        <a href="{{ route('dashboard') }}">{{ __('Dashboard') }}</a>
                    </li>
                    <li class="breadcrumb-item active">{{ __('Processing Products') }}</li>
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
                    <form action="{{ route('processing.index') }}" method="GET" role="search">
                        <div class="input-group">
                            <input type="text" name="term"
                                    placeholder="{{ __('Type processing code or start date or end date ...') }}"
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
                        <a class="btn btn-secondary" href="{{ route('processing.pdf') }}">
                            <i class="fas fa-download"></i> @lang('Export')
                        </a>
                        <a href="{{ route('processing.create') }}" class="btn btn-primary">
                            {{ __('Add Processing Product') }} <i class="fas fa-plus-circle"></i>
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
                        @if (count($steps) <= 6)
                            @foreach ($steps as $step) <th>{{ $step->step_name }}</th> @endforeach
                        @endif
                        <th>{{ __('Start Date') }}</th>
                        <th>{{ __('End Date') }}</th>
                        <th>{{ __('Status') }}</th>
                        <th class="text-right">{{ __('Action') }}</th>
                    </tr>
                    </thead>
                    <tbody>

                    @if ($processingProducts->total() > 0)
                        @foreach ($processingProducts as $key => $product)
                            <tr>
                                <td>{{ ++$key }}</td>
                                <td>
                                    <a href="{{ route('purchases.edit', $product->purchase->purchase_code) }}"
                                        title="{{ __('Edit pruchase') }}">{{ $helper->pruchaseCode($product->purchase->purchase_code) }}</a>
                                </td>
                                <td>
                                    <a href="{{ route('processing.edit', $product->slug) }}"
                                        title="{{ __('Edit processing') }}">{{ $helper->instance()->processingCode($product->processing_code) }}</a>
                                </td>
                                @if (count($steps) <= 6)
                                    @foreach ($steps as $step) <td>
                                        @foreach ($product->selectedStaff()->where('processing_step_id', $step->step_id)->get() as $staff)
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
                                <td class="text-right">
                                    <div class="btn-group">
                                        <button type="button"
                                                class="btn btn-secondary dropdown-toggle action-dropdown-toggle"
                                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <i class="fas fa-ellipsis-v"></i>
                                        </button>
                                        <div class="dropdown-menu dropdown-menu-right">
                                            @if ($product->isActive())
                                                <a href="{{ route('processing.status', $product->slug) }}"
                                                    class="dropdown-item"><i class="fas fa-window-close"></i>
                                                    {{ __('Inactive') }}</a>
                                            @else
                                                <a href="{{ route('processing.status', $product->slug) }}"
                                                    class="dropdown-item"><i class="fas fa-check-square"></i>
                                                    {{ __('Active') }}</a>
                                            @endif
                                            <a href="{{ route('processing.show', $product->slug) }}"
                                                class="dropdown-item"><i class="fas fa-eye"></i>
                                                {{ __('View') }}</a>
                                            <a href="{{ route('processing.edit', $product->slug) }}"
                                                class="dropdown-item"><i class="fas fa-edit"></i>
                                                {{ __('Edit') }}</a>
                                            <a href="{{ route('processing.delete', $product->slug) }}"
                                                class="dropdown-item delete-btn"
                                                data-msg="{{ __('Are you sure you want to delete this processing product?') }}"><i
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
                                    <p>{{ __('Sorry, no processing product found in the database. Create your very first processing product.') }}
                                    </p>
                                    <a href="{{ route('processing.create') }}" class="btn btn-primary">
                                        {{ __('Add Processing Product') }} <i class="fas fa-plus-circle"></i>
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
            {{ $processingProducts->links() }}
            <!-- pagination end -->
        </div>
    </div>

    <!-- /.content -->
@endsection
