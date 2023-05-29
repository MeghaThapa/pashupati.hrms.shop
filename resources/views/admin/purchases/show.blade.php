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
                <h1 class="m-0 text-dark">{{ __('View Purchase') }}</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item">
                        <a href="{{ route('dashboard') }}">{{ __('Dashboard') }}</a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="{{ route('purchases.index') }}">{{ __('Purchases') }}</a>
                    </li>
                    <li class="breadcrumb-item active">{{ __('View Purchase') }}</li>
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
                        <h3 class="card-title">{{ __('View purchase') }}:
                            {{ $helper->pruchaseCode($purchase->purchase_code) }}</h3>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body p-0">
                        <div class="row">
                            <div class="col-md-12 col-lg-12 table-responsive view-table">
                                <table class="table">
                                    <tbody>
                                        <tr>
                                            <td>
                                                <strong>{{ __('Purchased Code') }}:</strong>
                                                {{ $helper->pruchaseCode($purchase->purchase_code) }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <strong>{{ __('Supplier') }}:</strong>
                                                {{ $purchase->supplier->name }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <strong>{{ __('Purchase Products') }}:</strong>
                                                <div class="table-responsive">
                                                    <table class="table table-striped">
                                                        <thead>
                                                            <tr>
                                                                <th>#</th>
                                                                <th>{{ __('Product') }}</th>
                                                                <th>{{ __('Purchased Qty') }}</th>
                                                                <th>{{ __('Used') }}</th>
                                                                <th>{{ __('Return') }}</th>
                                                                <th>{{ __('Damage') }}</th>
                                                                <th>{{ __('Available') }}</th>
                                                                <th>{{ __('Unit Price') }}</th>
                                                                <th>{{ __('Discount') }}</th>
                                                                <th class="text-right">{{ __('Total') }}</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @foreach ($purchase->purchaseProducts as $key => $product)
                                                                @php
                                                                    $returnQty = $product->return_quantity > 0 ? $product->return_quantity : 0;
                                                                    $damageQty = $product->damage_quantity > 0 ? $product->damage_quantity : 0;
                                                                @endphp
                                                                <tr>
                                                                    <td>{{ ++$key }}</td>
                                                                    <td>{{ $product->product_name }}</td>
                                                                    <td>{{ $product->quantity }} {{ $product->unit }}
                                                                    </td>
                                                                    <td>{{ $product->usedQty() }} {{ $product->unit }}
                                                                    </td>
                                                                    <td>{{ $returnQty }} {{ $product->unit }}</td>
                                                                    <td>{{ $damageQty }} {{ $product->unit }}</td>
                                                                    <td>{{ $product->availableQuantity() }}
                                                                        {{ $product->unit }}</td>
                                                                    <td>{{ $helper->formattedCurrency($product->unit_price) }}
                                                                    </td>
                                                                    <td>{{ $helper->formattedCurrency($product->discountAmount()) }}
                                                                        ({{ $product->discount > 0 ? $product->discount : 0 }}%)
                                                                    </td>
                                                                    <td class="text-right">

                                                                        @if ($product->discount > 0)
                                                                            {{ $helper->instance()->formattedCurrency($product->quantity * $product->unit_price) }}
                                                                            -
                                                                            {{ $helper->formattedCurrency($product->discountAmount()) }}
                                                                            =
                                                                            {{ $helper->formattedCurrency($product->total) }}
                                                                        @else
                                                                            {{ $helper->formattedCurrency($product->total) }}
                                                                        @endif
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
                                                <strong>{{ __('Subtotal') }}:</strong>
                                                {{ $helper->formattedCurrency($purchase->sub_total) }}
                                                ({{ __('After reducing discount.') }})
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <strong>{{ __('Total Discount') }}:</strong>
                                                {{ $helper->formattedCurrency($purchase->discount) }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <strong>{{ __('Trasnport Cost') }}:</strong>
                                                +{{ $helper->formattedCurrency($purchase->trasnport) }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <strong>{{ __('Grand Total') }}:</strong>
                                                {{ $helper->formattedCurrency($purchase->total) }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <strong>{{ __('Total Paid') }}:</strong>
                                                {{ $helper->formattedCurrency($purchase->total_paid) }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <strong>{{ __('Total Due') }}:</strong>
                                                {{ $helper->formattedCurrency($purchase->total_due) }}
                                            </td>
                                        </tr>
                                        @if (!empty($purchase->purchaseReturn))
                                            <tr>
                                                <td>
                                                    <strong>{{ __('Refund Amount') }}:</strong>
                                                    {{ $helper->formattedCurrency($purchase->purchaseReturn->refund_amount) }}

                                                </td>
                                            </tr>
                                        @endif
                                        <tr>
                                            <td>
                                                <strong>{{ __('Payment Method') }}:</strong>
                                                {{ $purchase->payment_type }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <strong>{{ __('Note') }}:</strong>
                                                {{ $purchase->note }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <strong>{{ __('Status') }}:</strong>
                                                @if ($purchase->isActive())
                                                    <span class="badge badge-success">{{ __('Active') }}</span>
                                                @else
                                                    <span class="badge badge-warning">{{ __('Inactive') }}</span>
                                                @endif
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="col-md-12 col-lg-12 text-center justify-content-center align-self-center">
                                @if ($purchase->purchase_image)
                                    <img src="{{ $purchase->imagepath() }}" alt="{{ __('Purchase Image') }}"
                                        class="img-fluid" class="mt-3" />
                                @else
                                    <img src="{{ asset('img/placeholder.png') }}" alt="{{ __('Purchase Image') }}"
                                        class="img-fluid" class="mt-3" />
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="card-footer no-print">
                        <a href="{{ route('purchases.index') }}" class="btn btn-primary">
                            <i class="fas fa-long-arrow-alt-left"></i> {{ __('Go Back') }}
                        </a>
                        <a href="#" class="btn btn-secondary float-right print-btn"><i class="fas fa-print"></i>
                            {{ __('Print') }}</a>
                    </div>
                </div>
            </div>
            {{-- Return for this purchase --}}
            @if ($purchase->purchaseReturn()->count() > 0)
                <div class="row mt-5">
                    <div class="card col-md-12">
                        <div class="card-header">
                            <h3 class="card-title">{{ __('View returns products for purchase') }}:
                                {{ $helper->pruchaseCode($purchase->purchase_code) }}</h3>
                        </div>
                        <!-- /.card-header -->
                        @php
                            $purchaseReturn = $purchase->purchaseReturn()->get()[0];
                        @endphp

                        <div class="card-body p-0">
                            <div class="row">
                                <div class="col-md-12 col-lg-12 table-responsive view-table min-height-150">
                                    <table class="table">
                                        <tbody>
                                            <tr>
                                                <td>
                                                    <strong>{{ __('Return Reason') }}:</strong>
                                                    {{ $purchaseReturn->return_reason }}
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <strong>{{ __('Return Date') }}:</strong>
                                                    {{ \Carbon\Carbon::parse($purchaseReturn->return_date)->format('d-M-Y') }}
                                                </td>
                                            </tr>

                                            <tr>
                                                <td>
                                                    <strong>{{ __('Purchase Products') }}:</strong>
                                                    <div class="table-responsive">
                                                        <table class="table table-striped">
                                                            @foreach ($purchaseReturn->purchase->purchaseProducts as $key => $product)
                                                                @if ($key == 0)
                                                                    <thead>
                                                                        <tr>
                                                                            <th>#</th>
                                                                            <th>{{ __('Product') }}</th>
                                                                            <th>{{ __('Purchased Qty') }}</th>
                                                                            <th>{{ __('Return') }}</th>
                                                                            <th>{{ __('Unit Price') }}</th>
                                                                            <th class="text-right">{{ __('Total') }}</th>
                                                                        </tr>
                                                                    </thead>
                                                                @endif
                                                                <tr>
                                                                    <td>{{ ++$key }}</td>
                                                                    <td>{{ $product->product_name }}</td>
                                                                    <td>{{ $product->quantity }} {{ $product->unit }}
                                                                    </td>
                                                                    <td>{{ $product->return_quantity }}
                                                                        {{ $product->unit }}</td>
                                                                    <td>{{ $helper->formattedCurrency($product->unit_price) }}
                                                                    </td>
                                                                    <td class="text-right">
                                                                        {{ $helper->instance()->formattedCurrency($product->total) }}
                                                                    </td>
                                                                </tr>
                                                            @endforeach
                                                        </table>
                                                    </div>
                                                </td>
                                            </tr>

                                            <tr>
                                                <td>
                                                    <strong>{{ __('Refund Amount') }}:</strong>
                                                    {{ $helper->formattedCurrency($purchaseReturn->refund_amount) }}
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <strong>{{ __('Note') }}:</strong>
                                                    {{ $purchaseReturn->note }}
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <strong>{{ __('Status') }}:</strong>
                                                    @if ($purchaseReturn->isActive())
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

                    </div>
                </div>
            @endif

            {{-- Damage for this purchase --}}
            @if ($purchase->purchaseDamage()->count() > 0)
                <div class="row mt-5">
                    <div class="card col-md-12">
                        <div class="card-header">
                            <h3 class="card-title">{{ __('View damage products for purchase') }}:
                                {{ $helper->pruchaseCode($purchase->purchase_code) }}</h3>
                        </div>
                        <!-- /.card-header -->
                        @php
                            $damagePurchase = $purchase->purchaseDamage()->get()[0];
                        @endphp

                        <div class="card-body p-0 min-height-150">
                            <div class="row">
                                <div class="col-md-12 col-lg-12 table-responsive view-table">
                                    <table class="table">
                                        <tbody>
                                            <tr>
                                                <td>
                                                    <strong>{{ __('Purchased Code') }}:</strong>
                                                    {{ $helper->pruchaseCode($damagePurchase->purchase->purchase_code) }}
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <strong>{{ __('Damage Reason') }}:</strong>
                                                    {{ $damagePurchase->damage_reason }}
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <strong>{{ __('Damage Date') }}:</strong>
                                                    {{ \Carbon\Carbon::parse($damagePurchase->return_date)->format('d-M-Y') }}
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <strong>{{ __('Purchase Products') }}:</strong>
                                                    <div class="table-responsive">
                                                        <table class="table table-striped">
                                                            @foreach ($damagePurchase->purchase->purchaseProducts as $key => $product)
                                                                @if ($key == 0)
                                                                    <thead>
                                                                        <tr>
                                                                            <th>#</th>
                                                                            <th>{{ __('Product') }}</th>
                                                                            <th>{{ __('Purchased Qty') }}</th>
                                                                            <th>{{ __('Damage ') }}</th>
                                                                            <th>{{ __('Unit Price') }}</th>
                                                                            <th class="text-right">{{ __('Total') }}
                                                                            </th>
                                                                        </tr>
                                                                    </thead>
                                                                @endif
                                                                <tr>
                                                                    <td>{{ ++$key }}</td>
                                                                    <td>{{ $product->product_name }}</td>
                                                                    <td>{{ $product->quantity }}
                                                                        {{ $product->unit }}</td>
                                                                    <td>{{ $product->damage_quantity }}
                                                                        {{ $product->unit }}</td>
                                                                    <td>{{ $helper->formattedCurrency($product->unit_price) }}
                                                                    </td>
                                                                    <td class="text-right">
                                                                        {{ $helper->formattedCurrency($product->total) }}
                                                                    </td>
                                                                </tr>
                                                            @endforeach
                                                        </table>
                                                    </div>
                                                </td>
                                            </tr>

                                            <tr>
                                                <td>
                                                    <strong>{{ __('Note') }}:</strong>
                                                    {{ $damagePurchase->note }}
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <strong>{{ __('Status') }}:</strong>
                                                    @if ($damagePurchase->isActive())
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

                    </div>
                </div>
            @endif

            {{-- Processing products under this purchase --}}
            @if ($processingProducts->total() > 0)
                <div class="row mt-5">
                    <div class="card col-md-12">
                        <div class="card-header">
                            <h3 class="card-title">{{ __('View processing products under purchase') }}:
                                {{ $helper->pruchaseCode($purchase->purchase_code) }}</h3>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body p-0 table-responsive  min-height-150">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>{{ __('Purchased Code') }}</th>
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
                                    @foreach ($processingProducts as $key => $product)
                                        <tr>
                                            <td>{{ ++$key }}</td>
                                            <td>{{ $helper->pruchaseCode($product->purchase->purchase_code) }}</td>
                                            @if (count($steps) <= 6)
                                                @foreach ($steps as $step) <td>
                                                @foreach ($product->selectedStaff()->where('processing_step_id', $step->step_id)->get()
        as $staff)
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
                                                            <a href="{{ route('processing.status', $product->id) }}"
                                                                class="dropdown-item"><i class="fas fa-window-close"></i>
                                                                Inactive</a>
                                                        @else
                                                            <a href="{{ route('processing.status', $product->id) }}"
                                                                class="dropdown-item"><i class="fas fa-check-square"></i>
                                                                Active</a>
                                                        @endif
                                                        <a href="{{ route('processing.show', $product->id) }}"
                                                            class="dropdown-item"><i class="fas fa-eye"></i>
                                                            {{ __('View') }}</a>
                                                        <a href="{{ route('processing.edit', $product->id) }}"
                                                            class="dropdown-item"><i class="fas fa-edit"></i>
                                                            {{ __('Edit') }}</a>
                                                        <a href="{{ route('processing.delete', $product->id) }}"
                                                            class="dropdown-item delete-btn"
                                                            data-msg="{{ __('Are you sure to delete this processing product?') }}"><i
                                                                class="fas fa-trash"></i> {{ __('Delete') }}</a>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        @if ($processingProducts->total() > 10)
                            <div class="card-footer clearfix">
                                <div class="float-right">
                                    {{ $processingProducts->links() }}
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
