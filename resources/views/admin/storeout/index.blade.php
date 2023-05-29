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
                <h1 class="m-0 text-dark">{{ __('Storeout') }}</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item">
                        <a href="{{ route('dashboard') }}">{{ __('Dashboard') }}</a>
                    </li>
                    <li class="breadcrumb-item active">{{ __('Storeout') }}</li>
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
                    <form action="{{ route('storeout.index') }}" method="GET" role="search">
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
                        <a class="btn btn-secondary">
                            <i class="fas fa-download"></i> @lang('Export')
                        </a>
                        <a href="{{ route('storeout.create') }}" class="btn btn-primary">
                            {{ __('Add Storeout') }} <i class="fas fa-plus-circle"></i>
                        </a>
                    </div>
                </div>
            </div>

            <div class="p-0 table-responsive table-custom my-3">
                <table class="table">
                    <thead>
                        <tr>
                            <th>@lang('SN')</th>
                            <th>{{ __('Date') }}</th>
                            <th>{{ __('Receipt NO') }}</th>
                            <th>{{ __('For') }}</th>
                            <th>{{ __('Total') }}</th>
                            {{-- <th>{{ __('Status') }}</th> --}}
                            <th style="text-align: center !important;">{{ __('Action') }}</th>
                        </tr>
                    </thead>

                    <tbody>
                        @php
                            $i = 0;
                        @endphp
                        @foreach ($storeOutDatas as $storeOutData)
                            <tr>
                                <td>{{ ++$i }}</td>
                                <td>{{ $storeOutData->receipt_date }}</td>
                                <td>{{ $storeOutData->receipt_no }}</td>
                                <td>{{ $storeOutData->for }}</td>
                                <td>{{ $storeOutData->total_amount }}</td>
                                <td class="d-flex " style="text-align: center !important; gap:5px;">
                                    {{-- href="{{ route('storein.invoiceView', ['storeout_id' => $storeOutData->id]) }}" --}}
                                    {{-- <a>
                                        <button class="btn btn-info">
                                            <i class="fas fa-file-invoice"></i>
                                        </button>
                                    </a> --}}
                                    <button class="btn btn-danger" id="dltstorein" data-id="{{ $storeOutData->id }}">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                    {{-- href="{{ route('storein.editStorein', ['storeout_id' => $storeOutData->id]) }}" --}}
                                    <a href="{{ route('storeout.edit', ['storeout_id' => $storeOutData->id]) }}">
                                        <button class="btn btn-success">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                    </a>
                                </td>

                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <!-- /.card-body -->
        </div>
    </div>
    <!-- /.content -->
@endsection
