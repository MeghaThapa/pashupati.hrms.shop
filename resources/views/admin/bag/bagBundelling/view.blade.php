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
                <h1 class="m-0 text-dark">{{ __('View Store In') }}</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item">
                        <a href="{{ route('dashboard') }}">{{ __('Dashboard') }}</a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="{{ route('storein.index') }}">{{ __('Bag Bundel Index') }}</a>
                    </li>
                    <li class="breadcrumb-item active">{{ __('View Bundel Items') }}</li>
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
                        <h3 class="card-title">{{ __('BAG BUNDEL RECEIPT') }}:</h3>
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
                                    <p><strong>{{ __('DATE') }}:</strong>{{ $data->receipt_date }}</p>
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="font-weight-bold">
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="font-weight-bold">
                                    <p><strong>{{ __('Receipt. No.') }}:</strong> {{ $data->receipt_no }}</p>
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="card-body p-0">
                        <div class="row">

                            <div class="col-md-12 col-lg-12 table-responsive view-table">
                                <table class="table">
                                    <tbody>
                                        <tr>
                                            <td>
                                                <strong>{{ __('Bundeled Items') }}:</strong>
                                                <div class="table-responsive">
                                                    <table class="table table-striped table-bordered">
                                                        <thead>
                                                            <tr>
                                                                <th>S.N</th>
                                                                <th>{{ __('Bundel No') }}</th>
                                                                <th>{{ __('Brand Group') }}</th>
                                                                <th>{{ __('Brand Name') }}</th>
                                                                <th>{{ __('Qty Kg') }}</th>
                                                                <th>{{ __('Qty pices') }}</th>
                                                                <th>{{ __('Avg Weight') }}</th>

                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @php
                                                                $i = 1;
                                                            @endphp
                                                            @foreach ($data->bagBundelItems as $item)
                                                                <tr>
                                                                    <td>{{ $i++ }}</td>
                                                                    <td>{{ $item->bundel_no }}</td>
                                                                    <td>{{ $item->group->name }}</td>
                                                                    <td>{{ $item->bagBrand->name }}</td>
                                                                    <td>{{ $item->qty_in_kg }}</td>
                                                                    <td>{{ $item->qty_pcs }}</td>
                                                                    <td>{{ $item->average_weight }}</td>
                                                                </tr>
                                                            @endforeach
                                                        </tbody>
                                                        <tbody>
                                                            <tr>
                                                                <td colspan="6" class="text-right">Total Bundel</td>
                                                                <td colspan="2">
                                                                    {{ $data->total_bundle_quantity ? $data->total_bundle_quantity : '0' }}
                                                                    bundel
                                                                </td>
                                                            </tr>

                                                        </tbody>
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
                        <a href="{{ route('bagBundelling.index') }}" class="btn btn-primary">
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
