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
                <h1 class="m-0 text-dark">{{ __('Finished Report') }}</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item">
                        <a href="{{ route('dashboard') }}">{{ __('Dashboard') }}</a>
                    </li>
                    <li class="breadcrumb-item">{{ __('Product Report') }}</li>
                    <li class="breadcrumb-item active">{{ __('Finished Report') }}</li>
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
                    <h3 class="card-title">{{ __('Filter your finished products') }}</h3>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                    <form class="form-horizontal" action="{{ route('finished.report.filter') }}" method="post" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <!-- Date Picker Input -->
                            <div class="col-md-3 form-group">
                                <label for="fromDate">{{ __('Finished Date From') }}<span class="required-field">*</span></label>
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
                                <label for="toDate">{{ __('Finished Date To') }}<span class="required-field">*</span></label>
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
                                <label for="processingPro">{{ __('Processing Code') }}</label>
                                <select class="advance-select-box form-control @error('processingPro') is-invalid @enderror" id="processingPro" name="processingPro">
                                    <option value="">{{ __('All') }}</option>
                                    @foreach ($processingProducts as $product)
                                        <option value="{{ $product->id }}">
                                            {{ AppHelper::instance()->processingCode($product->processing_code) }}
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
            @if(isset($finishedProducts) && count($finishedProducts) > 0)
                <div class="card col-md-12">
                    <div class="card-header">
                        <h3 class="card-title">{{ __('Finished products for start date') }}: {{ $filters->fromDate }} {{ __('To') }} {{ $filters->toDate }}</h3>
                        <div class="card-tools">
                            <a href="{{ route('finished.create') }}" class="btn btn-block btn-primary">
                                {{ __('Add Finished') }} <i class="fas fa-plus-circle"></i>
                            </a>
                        </div>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body p-0 table-responsive min-height-150">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>{{ __('Finished Code') }}</th>
                                    <th>{{ __('Processing Code') }}</th>
                                    <th>{{ __('Purchase Code') }}</th>
                                    <th>{{ __('Product Type') }}</th>
                                    <th>{{ __('Sizes') }}</th>
                                    <th>{{ __('Quantities') }}</th>
                                    <th>{{ __('Finished Date') }}</th>
                                    <th>{{ __('Status') }}</th>
                                    <th class="text-right">{{ __('Action') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                    @foreach ($finishedProducts as $key => $product)
                                    <tr>
                                        <td>{{ ++$key  }}</td>
                                        <td>{{ AppHelper::instance()->finishedCode($product->finished_code) }}</td>
                                        <td>{{ AppHelper::instance()->processingCode($product->processingProduct->processing_code) }}</td>
                                        <td>{{ AppHelper::instance()->pruchaseCode($product->processingProduct->purchase->purchase_code) }}</td>
                                        <td>{{ $product->productType->name }} </td>
                                        <td>{{ $product->sizes }} </td>
                                        <td>{{ $product->quantities }} </td>
                                        <td>{{ date_format(date_create($product->finished_date), 'd-M-Y') }}</td>
                                        <td>
                                            @if($product->isActive())
                                                <span class="badge badge-success">{{ __('Active') }}</span>
                                            @else
                                                <span class="badge badge-warning">{{ __('Inactive') }}</span>
                                            @endif
                                        </td>
                                        <td class="text-right">
                                            <div class="btn-group">
                                                <button type="button" class="btn btn-secondary dropdown-toggle action-dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                    <i class="fas fa-ellipsis-v"></i>
                                                </button>
                                                <div class="dropdown-menu dropdown-menu-right">
                                                    @if($product->isActive())
                                                        <a href="{{ route('finished.status', $product->id)  }}" class="dropdown-item"><i class="fas fa-window-close"></i> {{ __('Inactive') }}</a>
                                                    @else
                                                        <a href="{{ route('finished.status', $product->id)  }}" class="dropdown-item"><i class="fas fa-check-square"></i> {{ __('Active') }}</a>
                                                    @endif
                                                    <a href="{{ route('finished.show', $product->id) }}" class="dropdown-item"><i class="fas fa-eye"></i> {{ __('View') }}</a>
                                                    <a href="{{ route('finished.edit', $product->id) }}" class="dropdown-item"><i class="fas fa-edit"></i> {{ __('Edit') }}</a>
                                                    <a href="{{ route('finished.delete', $product->id) }}" class="dropdown-item delete-btn" data-msg="{{ __('Are you sure to delete this finished product?') }}"><i class="fas fa-trash"></i> {{ __('Delete') }}</a>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <!-- /.card-body -->
                    @if($finishedProducts->total() > 15)
                        <div class="card-footer clearfix">
                            <div class="float-right">
                                {{ $finishedProducts->links() }}
                            </div>
                        </div>
                    @endif
                </div>
            @else
                @if(isset($filters))
                    <div class="w-100  alert alert-danger alert-dismissible fade show" role="alert">
                        <strong><i class="fas fa-folder-open"></i> </strong> {{ __('Sorry no finished porduct found for your filter!') }}
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
