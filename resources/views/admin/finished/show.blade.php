@php
use App\Helpers\AppHelper;
$helper = AppHelper::instance();
@endphp

@extends('layouts.admin')

@section('content')
    {{-- Content Header (Page header) --}}
    <div class="content-header mb-4">
        <div class="row align-items-center">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">{{ __('View Finished Product') }}</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item">
                        <a href="{{ route('dashboard') }}">{{ __('Dashboard') }}</a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="{{ route('finished.index') }}">{{ __('Finished Products') }}</a>
                    </li>
                    <li class="breadcrumb-item active">{{ __('View Product') }}</li>
                </ol>
            </div>
        </div>
    </div>
    {{-- end content-header --}}

    {{-- Main content --}}
    <div class="content">
        <div class="container">
            <div class="row">
                <div class="card col-md-12">
                    <div class="card-header">
                        <h3 class="card-title">{{ __('View finished product') }}:
                            {{ $helper->finishedCode($product->finished_code) }}</h3>
                    </div>
                    <div class="card-body p-0">
                        <div class="row">
                            <div class="col-md-12 col-lg-12 table-responsive view-table">
                                <table class="table">
                                    <tbody>
                                        <tr>
                                            <td>
                                                <strong>{{ __('Purchase Code') }}:</strong>
                                                {{ $helper->pruchaseCode($product->processingProduct->processing_code) }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <strong>{{ __('Processing Code') }}:</strong>
                                                {{ $helper->processingCode($product->processingProduct->processing_code) }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <strong>{{ __('Finished Code') }}:</strong>
                                                {{ $helper->finishedCode($product->finished_code) }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <strong>{{ __('Used Products & Quantities') }}:</strong>
                                                <div class="table-responsive">
                                                    <table class="table">
                                                        <thead>
                                                            <th>{{ __('Name') }}</th>
                                                            <th>{{ __('Purchase Qty') }}</th>
                                                            <th>{{ __('Used Qty') }}</th>
                                                            <th>{{ __('Available Qty') }}</th>
                                                            <th>{{ __('Unit Price') }}</th>
                                                            <th>{{ __('Total') }}</th>
                                                        </thead>
                                                        <tbody>
                                                            @foreach ($product->processingProduct->purchase->purchaseProducts as $key => $purchasePro)
                                                                <tr>
                                                                    <td>{{ $purchasePro->product_name }}</td>
                                                                    <td>{{ $purchasePro->quantity }}
                                                                        {{ $purchasePro->unit }}</td>
                                                                    <td>{{ $purchasePro->usedProducts()->where('finished_id', $product->id)->sum('used_quantity') }}
                                                                        {{ $purchasePro->unit }}</td>
                                                                    <td>{{ $purchasePro->availableQuantity() }}
                                                                        {{ $purchasePro->unit }}</td>
                                                                    <td>{{ $helper->formattedCurrency($purchasePro->unit_price) }}
                                                                    </td>
                                                                    <td>{{ $helper->formattedCurrency($purchasePro->total) }}
                                                                    </td>
                                                                </tr>
                                                            @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <strong>{{ __('Product Type') }}:</strong>
                                                {{ $product->productType->name }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <strong>{{ __('Product Finished Quantities') }}:</strong>
                                                <table>
                                                    <tr>
                                                        @for ($i = 0; $i < count($sizes); $i++)
                                                            <td>{{ __('Size') }}:{{ $sizes[$i] }}</td>
                                                        @endfor
                                                    </tr>
                                                    <tr>
                                                        @for ($i = 0; $i < count($quantities); $i++)
                                                            <td>{{ $quantities[$i] }}</td>
                                                        @endfor
                                                    </tr>
                                                </table>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <strong>{{ __('Product Rejected Quantities') }}:</strong>
                                                @if (!empty($rejectedQuantities))
                                                    <table>
                                                        <tr>
                                                            @for ($i = 0; $i < count($sizes); $i++)
                                                                <td>{{ __('Size') }}:{{ $sizes[$i] }}</td>
                                                            @endfor
                                                        </tr>
                                                        <tr>
                                                            @for ($i = 0; $i < count($rejectedQuantities); $i++)
                                                                @if ($rejectedQuantities[$i] == '') <td>0</td>
                                                            @else
                                                                <td>{{ $rejectedQuantities[$i] }}</td> @endif
                                                            @endfor
                                                        </tr>
                                                    </table>
                                                @else
                                                    <div class="alert alert-danger mt-3 w-50" role="alert">
                                                        @lang('No rejected product available')
                                                    </div>
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <strong>{{ __('Finished Date') }}:</strong>
                                                {{ date_format(date_create($product->finished_date), 'd-M-Y') }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <strong>{{ __('Note') }}:</strong> {{ $product->note }}
                                            </td>
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
                            @if ($product->finished_image)
                                <div class="col-md-12 col-lg-12 text-center justify-content-center align-self-center">
                                    <img src="{{ $product->imagepath() }}" alt="{{ __('Finished Product Image') }}"
                                        class="img-fluid" class="mt-3" />
                                </div>
                            @endif
                        </div>
                    </div>
                    <div class="card-footer no-print">
                        <a href="{{ route('finished.index') }}" class="btn btn-primary">
                            <i class="fas fa-long-arrow-alt-left"></i> {{ __('Go Back') }}
                        </a>
                        <a href="#" class="btn btn-secondary float-right print-btn"><i class="fas fa-print"></i>
                            {{ __('Print') }}</a>
                    </div>
                </div>
            </div>

            @if ($transferredProducts->total() > 0)
                <div class="row mt-5">
                    <div class="card col-md-12">
                        <div class="card-header">
                            <h3 class="card-title">{{ __('View transferred products under finished product') }}:
                                {{ $helper->finishedCode($product->finished_code) }}</h3>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body p-0 table-responsive min-height-150">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>@lang('#')</th>
                                        <th>{{ __('Finished Code') }}</th>
                                        <th>{{ __('Transferred Code') }}</th>
                                        <th>{{ __('Finished  Date') }}</th>
                                        <th>{{ __('Transferred Date') }}</th>
                                        <th>{{ __('Showroom') }}</th>
                                        <th>{{ __('Quantities') }}</th>
                                        <th>{{ __('Cartoon Number') }}</th>
                                        <th>{{ __('Status') }}</th>
                                        <th>{{ __('Action') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($transferredProducts as $key => $product)
                                        <tr>
                                            <td>{{ ++$key }}</td>
                                            <td>{{ $helper->finishedCode($product->finishedProduct->finished_code) }}
                                            </td>
                                            <td>{{ $helper->transferredCode($product->transferred_code) }}</td>
                                            <td>{{ date_format(date_create($product->finishedProduct->finished_date), 'd-M-Y') }}
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
                                                            date-msg="{{ __('Are you sure to delete this finished product?') }}"><i
                                                                class="fas fa-trash"></i> {{ __('Delete') }}</a>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        @if ($transferredProducts->total() > 10)
                            <div class="card-footer clearfix">
                                <div class="float-right">
                                    {{ $transferredProducts->links() }}
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            @endif
        </div>
    </div>
    {{-- end content --}}
@endsection
