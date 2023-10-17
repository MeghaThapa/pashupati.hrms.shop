@extends('layouts.admin')
@section('content')
    <!-- Content Header (Page header) -->
    <div class="content-header mb-4">
        <div class="row align-items-center">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">{{ __('Purchase Orders') }}</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item">
                        <a href="{{ route('dashboard') }}">{{ __('Dashboard') }}</a>
                    </li>
                    <li class="breadcrumb-item active">{{ __('Purchase Orders') }}</li>
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
            <div id="RawMaterialError" class="alert alert-danger" hidden></div>

            <div class="p-3 card card-body mt-4">
                <div class="row">
                    <div class="col-sm-3">
                        <h3>Indent No: {{ $purchaseOrder->indent_no }}</h3>
                    </div>
                    <div class="col-sm-9">
                        <span style="float: right;">Date: {{ $purchaseOrder->date }}</span>
                    </div>
                </div>
                <table class="table table-bordered" id="primaryTable">
                    <thead>
                        <tr>
                            <th>@lang('S.No.')</th>
                            <th>{{ __('Item Name') }}</th>
                            <th>{{ __('Parts No.') }}</th>
                            <th>{{ __('Item Category') }}</th>
                            <th>{{ __('Qty in Stock') }}</th>
                            <th>{{ __('Req. Qty') }}</th>
                            <th>{{ __('Last Purchase From') }}</th>
                            <th>{{ __('Purchase Rate') }}</th>
                            <th>{{ __('Remarks') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $i = 0;
                        @endphp
                        @foreach($purchaseOrder->purchaseOrderItems as $purchaseOrderItem)
                        <tr>
                            <td>{{ ++$i }}</td>
                            <td>{{ $purchaseOrderItem->item_name }}</td>
                            <td>{{ $purchaseOrderItem->itemsOfStoreins->pnumber }}</td>
                            <td>{{ $purchaseOrderItem->itemsOfStoreins->storeinCategory->name }}</td>
                            <td>{{ $purchaseOrderItem->stock_quantity }}</td>
                            <td>{{ $purchaseOrderItem->req_quantity }}</td>
                            <td>{{ $purchaseOrderItem->last_purchase_from }}</td>
                            <td>{{ $purchaseOrderItem->purchase_rate }}</td>
                            <td>{{ $purchaseOrderItem->remarks }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="row mt-4">
                    <div class="col-sm-4">
                        Prepared By <br/>
                        {{ $purchaseOrder->preparedBy->name }}
                    </div>
                    <div class="col-sm-4" style="text-align: center;">
                        Store Incharge <br/>
                        ..............
                    </div>
                    <div class="col-sm-4" style="text-align: right">
                        Authorized By <br/>
                        .............
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
