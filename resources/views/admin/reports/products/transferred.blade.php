@php
    use \App\Helpers\AppHelper;
@endphp

@extends('layouts.admin')

@section('extra-style')
<link href="{{ asset('css/select2/select2.min.css') }}" rel="stylesheet" />
<link href="{{ asset('css/select2/select2-bootstrap4.css') }}" rel="stylesheet" />
<link href="{{ asset('css/bootstrap-datepicker.css') }}" rel="stylesheet" />
<link href="{{ asset('css/custom-datepicker.css') }}" rel="stylesheet" />
@endsection

@section('content')
<!-- Content Header (Page header) -->
<div class="content-header mb-4">
    <div>
        <div class="row">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">{{ __('Transferred Report') }}</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item">
                        <a href="{{ route('dashboard') }}">{{ __('Dashboard') }}</a>
                    </li>
                    <li class="breadcrumb-item">{{ __('Product Report') }}</li>
                    <li class="breadcrumb-item active">{{ __('Transferred Report') }}</li>
                </ol>
            </div>
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
                    <h3 class="card-title">{{ __('Filter your transferred products') }}</h3>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                    <form class="form-horizontal" action="{{ route('transferred.report.filter') }}" method="post" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <!-- Date Picker Input -->
                            <div class="col-md-3 form-group">
                                <label for="fromDate">{{ __('Transferred Date From') }}<span class="required-field">*</span></label>
                                <div class="datepicker date input-group p-0 shadow-sm">
                                    <input type="text" placeholder="{{\Carbon\Carbon::today()->add(-7, 'days')->format('Y-m-d')}}" class="@error('fromDate') is-invalid @enderror form-control py-4 px-4" id="fromDate" name="fromDate" required>
                                    <div class="input-group-append">
                                        <span class="input-group-text px-4">
                                            <i class="fas fa-clock"></i>
                                        </span>
                                    </div>
                                </div>
                                @error('fromDate')
                                    <span class="invalid-feedback date-invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="col-md-3 form-group">
                                <label for="toDate">{{ __('Transferred Date To') }}<span class="required-field">*</span></label>
                                <div class="datepicker date input-group p-0 shadow-sm">
                                    <input type="text" placeholder="{{\Carbon\Carbon::today()->format('Y-m-d')}}" class="@error('toDate') is-invalid @enderror form-control py-4 px-4" id="toDate" name="toDate" required>
                                    <div class="input-group-append"><span class="input-group-text px-4"><i class="fas fa-clock"></i></span></div>
                                </div>
                                @error('toDate')
                                    <span class="invalid-feedback date-invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="col-md-4 form-group">
                                <label for="finishedPro">{{ __('Finished Code') }}</label>
                                <select class="advance-select-box form-control @error('finishedPro') is-invalid @enderror" id="finishedPro" name="finishedPro">
                                    <option value="">{{ __('All') }}</option>
                                    @foreach ($finishedProducts as $product)
                                        <option value="{{ $product->id }}">
                                            {{ AppHelper::instance()->finishedCode($product->finished_code) }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('purchase')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="col-md-2">
                                <button type="submit" class="btn btn-primary mt-25">
                                    <i class="fas fa-filter"></i> {{ __('Filter') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            @if(isset($transferredProducts) && count($transferredProducts) > 0)
                <div class="card col-md-12">
                    <div class="card-header">
                        <h3 class="card-title">{{ __('Transferred products for start date') }}: {{ $filters->fromDate }} {{ __('To') }} {{ $filters->toDate }}</h3>
                        <div class="card-tools">
                            <a href="{{ route('transferred.create') }}" class="btn btn-block btn-primary">
                                {{ __('Add Transferred') }} <i class="fas fa-plus-circle"></i>
                            </a>
                        </div>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body p-0 table-responsive min-height-150">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>{{ __('Transferred Code') }}</th>
                                    <th>{{ __('Finished Code') }}</th>
                                    <th>{{ __('Purchase Code') }}</th>
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
                                        <td>{{ ++$key  }}</td>
                                        <td>{{ AppHelper::instance()->transferredCode($product->transferred_code) }}</td>
                                        <td>{{ AppHelper::instance()->finishedCode($product->finishedProduct->finished_code) }}</td>
                                        <td>{{ AppHelper::instance()->pruchaseCode($product->finishedProduct->processingProduct->purchase->purchase_code) }}</td>
                                        <td>{{ date_format(date_create($product->finishedProduct->finished_date), 'd-M-Y') }}</td>
                                        <td>{{ date_format(date_create($product->transferred_date), 'd-M-Y') }}</td>
                                        <td>{{ $product->showroom->name }} </td>
                                        <td>
                                            {{ $product->finishedProduct->sizes }}<br/>
                                            {{ $product->transferred_quantities }}
                                        </td>
                                        <td>{{ $product->cartoon_number }} </td>
                                        <td>
                                            @if($product->isActive())
                                                <span class="badge badge-success">{{ __('Active') }}</span>
                                            @else
                                                <span class="badge badge-warning">{{ __('Inactive') }}</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="btn-group">
                                                <button type="button" class="btn btn-secondary dropdown-toggle action-dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                    <i class="fas fa-ellipsis-v"></i>
                                                </button>
                                                <div class="dropdown-menu dropdown-menu-right">
                                                    <a href="{{ route('transferred.show', $product->id) }}" class="dropdown-item"><i class="fas fa-eye"></i> {{ __('View') }}</a>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <!-- /.card-body -->
                    @if($transferredProducts->total() > 15)
                        <div class="card-footer clearfix">
                            <div class="float-right">
                                {{ $transferredProducts->links() }}
                            </div>
                        </div>
                    @endif
                </div>
            @else
                @if(isset($filters))
                    <div class="w-100  alert alert-danger alert-dismissible fade show" role="alert">
                        <strong><i class="fas fa-folder-open"></i> </strong> {{ __('Sorry no transferred porduct found for your filter!') }}
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                @endif
            @endif
        </div>
    </div>
</div>
<!-- /.content -->
@endsection


@section('extra-script')
<script src="{{ asset('js/select2/select2.min.js') }}"></script>
<script src="{{ asset('js/bootstrap-datepicker.min.js') }}"></script>
@endsection
