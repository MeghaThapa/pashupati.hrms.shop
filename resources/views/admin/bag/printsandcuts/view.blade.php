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
                <h1 class="m-0 text-dark">{{ __('Prints and cuts ') }}</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item">
                        <a href="{{ route('dashboard') }}">{{ __('Dashboard') }}</a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="{{ route('prints.and.cuts.index') }}">{{ __('Prints and cuts') }}</a>
                    </li>
                    <li class="breadcrumb-item active">{{ __('View Prints and Cuts Details') }}</li>
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
                        <h3 class="card-title">{{ __('PRINTS AND CUTS RECEIPT') }}:</h3>
                        <img class="lg-logo" src="{{ asset('img/9549253661679541318.png') }}" alt=""
                            style="height:30px;">
                    </div>
                    <!-- /.card-header -->
                    <div class="store_section">
                        <div class="row p-3">
                            <div class="col-sm-3">
                                <div class="font-weight-bold">
                                    <p><strong>{{ __('UNIT') }}:</strong> P.S.I(Sonapur) </p>
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="font-weight-bold">
                                    <p><strong>{{ __('SR. No.') }}:</strong>
                                        {{ $printedAndCuttedRollsEntryData->receipt_number }} </p>
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="font-weight-bold">
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="font-weight-bold">
                                </div>
                            </div>
                        </div>
                        <div class="row p-3">
                            <div class="col-sm-12">
                                <div class="store_name">
                                    <p><strong>{{ __('Date') }}:</strong> {{ $printedAndCuttedRollsEntryData->date }}
                                    </p>
                                </div>
                            </div>
                        </div>
                        {{-- <div class="row p-3">
                            <div class="col-sm-6">
                                <div class="store_name">
                                    <p><strong>{{ __('Supplier Name') }}:</strong> {{ $storein->supplier->name }}</p>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="store_name">
                                    <p><strong>{{ __('Store In Type') }}:</strong> {{ $storein->storeintype->name }} </p>
                                </div>
                            </div>
                        </div>
                        <div class="row p-3">
                            <div class="col-sm-6">
                                <div class="store_name">
                                    <p><strong>{{ __('Payment Method') }}:</strong>
                                        {{ $storein->payment_method_id != null ? $storein->paymentMethod->name : 'Not Paid' }}
                                    </p>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="store_name">
                                    <p><strong>{{ __('Date') }}:</strong> {{ $storein->purchase_date }}</p>
                                </div>
                            </div>
                        </div> --}}
                    </div>
                    <div class="card-body p-0">
                        <div class="row">

                            <div class="col-md-12 col-lg-12 table-responsive view-table">
                                <table class="table">
                                    <tbody>
                                        <tr>
                                            <td>
                                                <strong>{{ __('Purchase Products') }}:</strong>
                                                <div class="table-responsive">
                                                    <table class="table table-striped table-bordered">
                                                        <thead>
                                                            <tr>
                                                                <th>S.N</th>
                                                                <th>{{ __('Fabric') }}</th>
                                                                <th>{{ __('Roll No') }}</th>
                                                                <th>{{ __('Net Weight') }}</th>
                                                                <th>{{ __('Cut Length') }}</th>
                                                                <th>{{ __('brand bag') }}</th>
                                                                <th>{{ __('request bag') }}</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @php
                                                                $i = 1;
                                                                $totalBag = 0;
                                                            @endphp
                                                            @foreach ($printedAndCuttedRollsEntryData->printingAndCuttingBagItems as $items)
                                                                <tr>
                                                                    <td>{{ $i++ }}</td>
                                                                    <td>{{ $items->fabric->name }}</td>
                                                                    <td>{{ $items->roll_no }}</td>
                                                                    <td>{{ $items->net_weight }}</td>
                                                                    <td>{{ $items->cut_length }}</td>
                                                                    @if ($items && $items->brandBag)
                                                                        <td>{{ $items->brandBag->name }}</td>
                                                                    @else
                                                                        <td>null</td>
                                                                    @endif
                                                                    <td>{{ number_format($items->req_bag, 2) }}</td>
                                                                </tr>
                                                                @php
                                                                    $totalBag += $items->req_bag;
                                                                @endphp
                                                            @endforeach
                                                        </tbody>
                                                        <tfoot>
                                                            <tr style="font-weight:bold;">
                                                                <td>
                                                                    Total:
                                                                </td>
                                                                <td>
                                                                    {{ $totalBag }}
                                                                </td>
                                                            </tr>
                                                        </tfoot>
                                                        {{-- <tbody>
                                                            <tr>
                                                                <td colspan="4" class="text-right">Sub Total</td>
                                                                <td colspan="2">
                                                                    Rs.{{ $storein->sub_total ? $storein->sub_total : '0' }}
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td colspan="4" class="text-right">Total Discount</td>
                                                                <td colspan="2">
                                                                    Rs.
                                                                    {{ $storein->total_discount ? $storein->total_discount : '0' }}
                                                                    {{ $storein->discount_percent ? "($storein->discount_percent%)" : '' }}
                                                                </td>
                                                            </tr>
                                                            @if (count($charges) > 0)
                                                                @foreach ($charges as $charge)
                                                                    <tr>
                                                                        <td colspan="4" class="text-right">
                                                                            {{ $charge->charge_name }}</td>
                                                                        <td colspan="2">
                                                                            Rs.{{ $charge->charge_total }}
                                                                            {{ $charge->charge_operator == '%' ? "($charge->charge_amount%)" : '' }}
                                                                        </td>
                                                                    </tr>
                                                                @endforeach
                                                            @endif
                                                            <tr>
                                                                <td colspan="4" class="text-right">Grand Total</td>
                                                                <td colspan="2">Rs.
                                                                    {{ $storein->grand_total ? $storein->grand_total : '0' }}
                                                                </td>
                                                            </tr>
                                                        </tbody> --}}
                                                    </table>
                                                </div>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
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
        </div>
    </div>
    <!-- /.content -->
@endsection
