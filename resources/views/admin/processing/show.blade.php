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
                <h1 class="m-0 text-dark">{{ __('View Processing') }}</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item">
                        <a href="{{ route('dashboard') }}">{{ __('Dashboard') }}</a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="{{ route('processing.index') }}">{{ __('Processing Product') }}</a>
                    </li>
                    <li class="breadcrumb-item active">{{ __('View Processing') }}</li>
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
                        <h3 class="card-title">{{ __('View processing product for purchase') }}:
                            {{ $helper->pruchaseCode($processing->purchase->purchase_code) }}</h3>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body p-0">
                        <div class="row">
                            <div class="col-md-12 col-lg-6 text-center justify-content-center align-self-center">
                                @if ($processing->processing_image)
                                    <img src="{{ $processing->imagepath() }}" alt="{{ __('Procssing Image') }}"
                                        class="img-fluid" class="mt-3" />
                                @else
                                    <img src="{{ asset('img/placeholder.png') }}" alt="{{ __('Processing Image') }}"
                                        class="img-fluid" class="mt-3" />
                                @endif

                            </div>
                            <div class="col-md-12 col-lg-6 table-responsive view-table">
                                <table class="table">
                                    <tbody>
                                        <tr>
                                            <td><strong>{{ __('Purchased Code') }}:</strong>
                                                {{ $helper->pruchaseCode($processing->purchase->purchase_code) }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><strong>{{ __('Processing Code') }}:</strong>
                                                {{ $helper->processingCode($processing->processing_code) }}
                                            </td>
                                        </tr>
                                        @foreach ($steps as $step)
                                            <tr>
                                                <td><strong>{{ $step->step_name }}:</strong>
                                                    @foreach ($processing->selectedStaff()->where('processing_step_id', $step->step_id)->get()
        as $key => $staff)
                                                        <a href="{{ route('staff.show', $staff->id) }}"><span
                                                                class="badge badge-success">{{ $staff->name }}
                                                            </span></a>
                                                    @endforeach
                                                </td>
                                            </tr>
                                        @endforeach
                                        <tr>
                                            <td><strong>{{ __('Start Date') }}:</strong>
                                                {{ date_format(date_create($processing->start_date), 'd-M-Y') }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>{{ __('End Date') }}:</strong>
                                                {{ date_format(date_create($processing->end_date), 'd-M-Y') }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>{{ __('Note') }}:</strong> {{ $processing->note }}</td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <strong>{{ __('Status') }}:</strong>
                                                @if ($processing->isActive())
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
                        <a href="{{ route('processing.index') }}" class="btn btn-primary">
                            <i class="fas fa-long-arrow-alt-left"></i> {{ __('Go Back') }}
                        </a>
                        <a href="#" class="btn btn-secondary float-right print-btn"><i class="fas fa-print"></i>
                            {{ __('Print') }}</a>
                    </div>
                </div>
            </div>

            @if ($finishedProducts->total() > 0)
                <div class="row mt-5">
                    <div class="card col-md-12">
                        <div class="card-header">
                            <h3 class="card-title">{{ __('View finished products under processing product') }}:
                                {{ AppHelper::instance()->processingCode($processing->processing_code) }}</h3>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body p-0 table-responsive min-height-150">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th class="text-right">#</th>
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
                                    @foreach ($finishedProducts as $key => $finishedProduct)
                                        <tr>
                                            <td>{{ ++$key }}</td>
                                            <td>{{ $helper->pruchaseCode($finishedProduct->processingProduct->purchase->purchase_code) }}
                                            </td>
                                            <td>{{ $helper->processingCode($finishedProduct->processingProduct->processing_code) }}
                                            </td>
                                            <td>{{ $helper->processingCode($finishedProduct->finished_code) }}
                                            </td>
                                            <td>{{ $finishedProduct->productType->name }} </td>
                                            <td>{{ $finishedProduct->sizes }} </td>
                                            <td>{{ $finishedProduct->quantities }} </td>
                                            <td>{{ date_format(date_create($finishedProduct->finished_date), 'd-M-Y') }}
                                            </td>
                                            <td>
                                                @if ($finishedProduct->isActive())
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
                                                        @if ($finishedProduct->isActive())
                                                            <a href="{{ route('finished.status', $finishedProduct->id) }}"
                                                                class="dropdown-item"><i class="fas fa-window-close"></i>
                                                                {{ __('Inactive') }}</a>
                                                        @else
                                                            <a href="{{ route('finished.status', $finishedProduct->id) }}"
                                                                class="dropdown-item"><i class="fas fa-check-square"></i>
                                                                {{ __('Active') }}</a>
                                                        @endif
                                                        <a href="{{ route('finished.show', $finishedProduct->id) }}"
                                                            class="dropdown-item"><i class="fas fa-eye"></i>
                                                            {{ __('View') }}</a>
                                                        <a href="{{ route('finished.edit', $finishedProduct->id) }}"
                                                            class="dropdown-item"><i class="fas fa-edit"></i>
                                                            {{ __('Edit') }}</a>
                                                        <a href="{{ route('finished.delete', $finishedProduct->id) }}"
                                                            class="dropdown-item delete-btn"
                                                            data-msg="{{ __('Are you sure to delete this finished product?') }}"><i
                                                                class="fas fa-trash"></i> {{ __('Delete') }}</a>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        @if ($finishedProducts->total() > 10)
                            <div class="card-footer clearfix">
                                <div class="float-right">
                                    {{ $finishedProducts->links() }}
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            @endif
        </div>
    </div>
    <!-- /.content -->
@endsection
