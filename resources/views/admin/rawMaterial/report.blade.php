@extends('layouts.admin')
@section('content')
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
                        <a class="btn btn-secondary" href="{{ route('storein.pdf') }}">
                            <i class="fas fa-download"></i> @lang('Export')
                        </a>
                        <a href="{{ route('rawMaterial.godamTransferDetail') }}" class="btn btn-success">
                            {{ __('Godam Transfer details') }}<i class="fas fa-plus-circle"></i>
                        </a>
                        <a href="{{ route('rawMaterial.create') }}" class="btn btn-primary">
                            {{ __('Add Raw Materials') }} <i class="fas fa-plus-circle"></i>
                        </a>
                    </div>
                </div>
            </div>
            <div class="p-3 card card-body">
                @foreach($mergedArray as $key => $result)
                <h5>{{ $key }}</h5>
                <table class="table table-bordered" id="rawMaterialTable" style="margin-bottom: 120px;">
                    <thead>
                        <tr>
                            <th></th>
                            <th colspan="4">Received</th>
                            <th colspan="4">Issue</th>
                            <th></th>
                        </tr>
                    </thead>
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Opening</th>
                            <th>Import</th>
                            <th>Local</th>
                            <th>From Godam</th>
                            <th>Tape</th>
                            <th>Lam</th>
                            <th>N.W</th>
                            <th>Sales</th>
                            <th>To Godam</th>
                            <th>Closing</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($result as $dateKey => $datewiseQty)
                        <tr>
                            <td>{{ $dateKey }}</td>
                            <td> {{ isset($datewiseQty['opening_quantity'])? $datewiseQty['opening_quantity'] :'' }} </td>
                            <td>@if(isset($datewiseQty['import_from']) && $datewiseQty['import_from'] == 'import' ) {{ $datewiseQty['total_quantity'] }} @else 0 @endif</td>
                            <td>@if(isset($datewiseQty['import_from']) && $datewiseQty['import_from'] == 'local' ) {{ $datewiseQty['total_quantity'] }} @else 0 @endif</td>
                            <td>@if(isset($datewiseQty['import_from']) && $datewiseQty['import_from'] == 'godam' && $datewiseQty['from_godam_id']==2 && $datewiseQty['to_godam_id'] == 1 ) {{ $datewiseQty['total_quantity'] }} @else 0 @endif</td>
                            <td>{{ $datewiseQty['tape plant'] ?? 0 }} </td>
                            <td>{{ $datewiseQty['lamination plant'] ?? 0 }}</td>
                            <td>{{ $datewiseQty['nonwoven plant'] ?? 0 }}</td>
                            <td>{{ $datewiseQty['sales_quantity'] ?? 0 }}</td>
                            <td>@if(isset($datewiseQty['import_from']) && $datewiseQty['import_from'] == 'godam' && $datewiseQty['from_godam_id']==1 && $datewiseQty['to_godam_id'] == 2  ) {{ $datewiseQty['total_quantity'] }} @else 0 @endif</td>
                            <td>@if(isset($datewiseQty['import_from']) && $datewiseQty['import_from'] == 'closing' ) {{ $datewiseQty['total_quantity'] }} @else 0 @endif</td>

                        </tr>
                        @endforeach
                    </tbody>
                </table>
                @endforeach
            </div>
        @endsection
