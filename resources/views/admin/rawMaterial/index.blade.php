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
                        <a class="btn btn-secondary" href="{{ route('storein.pdf') }}">
                            <i class="fas fa-download"></i> @lang('Export')
                        </a>
                        <a href="{{ route('rawMaterial.create') }}" class="btn btn-primary">
                            {{ __('Add Raw Materials') }} <i class="fas fa-plus-circle"></i>
                        </a>
                    </div>
                </div>
            </div>
            <div class="p-3 card card-body">
                <table class="table table-bordered" id="rawMaterialTable">
                    <thead>
                        <tr>
                            <th>@lang('#')</th>
                            <th>{{ __('Date') }}</th>
                            <th>{{ __('Receipt No') }}</th>
                            <th>{{ __('Supplier Name') }}</th>
                            <th>{{ __('PP No') }}</th>
                            <th>{{ __('Storein Type') }}</th>
                            <th>{{ __('From Godam') }}</th>
                            <th>{{ __('To Godam') }}</th>
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
                var table = $('#rawMaterialTable').DataTable({
                    lengthMenu: [
                        [30, 40, 50, -1],
                        ['30 rows', '40 rows', '50 rows', 'Show all']
                    ],
                    style: 'bootstrap4',
                    processing: true,
                    serverSide: true,
                    ajax: "{{ route('rawMaterial.dataTable') }}",
                    columns: [{
                            data: 'DT_RowIndex'
                        },
                        {
                            data: 'date'
                        },
                        {
                            data: 'receipt_no'
                        },
                        {
                            data: 'supplier_id'
                        },
                        {
                            data: 'pp_no'
                        },
                        {
                            data: 'storein_type_id'
                        },
                        {
                            data: 'from_godam_id'
                        },
                        {
                            data: 'to_godam_id'
                        },

                        {
                            data: 'action',
                            orderable: true,
                            searchable: true,
                        },
                    ]
                });
            </script>
        @endsection
