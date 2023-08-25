@extends('layouts.admin')
@section('content')
    <!-- Content Header (Page header) -->
    <div class="content-header mb-4">
        <div class="row align-items-center">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">{{ __('Storein') }}</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item">
                        <a href="{{ route('dashboard') }}">{{ __('Dashboard') }}</a>
                    </li>
                    <li class="breadcrumb-item active">{{ __('Storein') }}</li>
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
            <div class="row">
                <div class="col-lg-3 col-md-5 col-6 mb-2">
                    <form action="{{ route('storein.index') }}" method="GET" role="search">
                        <div class="input-group">
                            <input type="text" name="term" placeholder="{{ __('Type code or supplier or date ...') }}"
                                class="form-control" autocomplete="off" value="{{ request('term') ? request('term') : '' }}"
                                required>
                            <span class="input-group-append">
                                <button type="submit" class="btn btn-primary">{{ __('Search') }}</button>
                            </span>
                        </div>
                    </form>
                </div>
                <div class="col-lg-9 col-md-7 col-6">
                    <div class="card-tools text-md-right">

                        {{-- <a href="{{ route('rawMaterial.godamTransferDetail') }}" class="btn btn-success">
                            {{ __('Godam Transfer details') }}<i class="fas fa-plus-circle"></i>
                        </a> --}}
                        <a href="{{ route('rawMaterialSalesEntry.create') }}" class="btn btn-primary">
                            {{ __('Add RawMaterial Sales') }} <i class="fas fa-plus-circle"></i>
                        </a>
                    </div>
                </div>
            </div>
            <div class="p-3 card card-body">
                <table class="table table-bordered" id="rawMaterialSalesTable">
                    <thead>
                        <tr>
                            <th>@lang('#')</th>
                            <th>{{ __('Bill Data') }}</th>
                            <th>{{ __('Bill No') }}</th>
                            <th>{{ __('Supplier Name') }}</th>
                            <th>{{ __('Godam Name') }}</th>
                            <th>{{ __('Challan No') }}</th>
                            <th>{{ __('Do No') }}</th>
                            <th>{{ __('Gp No') }}</th>
                            <th>{{ __('Through') }}</th>
                            <th>{{ __('sale for') }}</th>
                            <th>{{ __('Status') }}</th>
                            <th>{{ __('Action') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        @endsection
        @section('extra-script')
            <script>
                $(document).ready(function() {
                    var table = $('#rawMaterialSalesTable').DataTable({
                        lengthMenu: [
                            [30, 40, 50, -1],
                            ['30 rows', '40 rows', '50 rows', 'Show all']
                        ],
                        style: 'bootstrap', // Corrected 'style' option
                        processing: true,
                        serverSide: true,
                        ajax: "{{ route('rawMaterialSalesEntry.yajraDatatables') }}",
                        columns: [{
                                data: 'DT_RowIndex'
                            },
                            {
                                data: 'bill_date'
                            },
                            {
                                data: 'bill_no'
                            },
                            {
                                data: 'supplier.name'
                            },
                            {
                                data: 'godam.name'
                            },
                            {
                                data: 'challan_no'
                            },
                            {
                                data: 'do_no'
                            },
                            {
                                data: 'gp_no'
                            },
                            {
                                data: 'through'
                            },
                            {
                                data: 'sale_for'
                            },
                            {
                                data: 'statusBtn'
                            },
                            {
                                data: 'action'
                            },
                        ],


                    });
                });
            </script>
        @endsection
