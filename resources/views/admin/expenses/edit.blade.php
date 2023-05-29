@extends('layouts.admin')

@section('extra-style')
<link href="{{ asset('css/select2/select2.min.css') }}" rel="stylesheet" />
<link href="{{ asset('css/select2/select2-bootstrap4.css') }}" rel="stylesheet" />
@endsection

@section('content')
    <!-- Content Header (Page header) -->
    <div class="content-header mb-4">
        <div class="row align-items-center">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">{{ __('Edit Expense') }}</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item">
                        <a href="{{ route('dashboard') }}">{{ __('Dashboard') }}</a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="{{ route('expenses.index') }}">{{ __('Expenses') }}</a>
                    </li>
                    <li class="breadcrumb-item active">{{ __('Edit Expense') }}</li>
                </ol>
            </div>
        </div>
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <div class="content">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">{{ __('Edit Expense') }}: {{ $expense->expense_reason }}</h3>
                <div class="card-tools">
                    <a href="{{ route('expenses.index') }}" class="btn btn-block btn-primary">
                        <i class="fas fa-long-arrow-alt-left"></i> {{ __('Go Back') }}
                    </a>
                </div>
            </div>
            <!-- /.card-header -->
            <div class="card-body p-0">
                <form class="form-horizontal" action="{{ route('expenses.update', $expense->slug) }}" method="post" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 form-group">
                                <label for="categoryName">{{ __('Expense Category') }}<span class="required-field">*</span></label>
                                <select class="advance-select-box form-control @error('categoryName') is-invalid @enderror" id="categoryName" name="categoryName"  required>
                                    @foreach($categories as $key => $category)
                                        <option value="{{ $category->id }}" {{ $expense->exp_cat_id == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                                    @endforeach
                                </select>
                                @error('categoryName')
                                <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                @enderror
                            </div>
                            <div class="col-md-6 form-group">
                                <label for="expenseReason">{{ __('Expense Reason') }}<span class="required-field">*</span></label>
                                <input type="text" class="form-control @error('expenseReason') is-invalid @enderror" id="expenseReason" name="expenseReason" placeholder="{{ __('Expense Reason') }}" value="{{ $expense->expense_reason }}" required>
                                @error('expenseReason')
                                <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                @enderror
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 form-group">
                                <label for="expenseAmount">{{ __('Expense Amount') }}<span class="required-field">*</span></label>
                                <input type="number" step="0.01" class="form-control @error('expenseAmount') is-invalid @enderror" id="expenseAmount" name="expenseAmount" placeholder="{{ __('Expense Amount') }}" value="{{ $expense->amount }}" required>
                                @error('expenseAmount')
                                <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                @enderror
                            </div>
                            <div class="col-md-6 form-group">
                                <label for="expenseDate">{{ __('Expense Date') }}<span class="required-field">*</span></label>
                                <input type="date" placeholder="{{\Carbon\Carbon::today()->format('Y-m-d')}}" class="@error('expenseDate') is-invalid @enderror form-control" id="expenseDate" name="expenseDate" value="{{ $expense->expense_date }}" required>
                                @error('expenseDate')
                                <span class="invalid-feedback date-invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                @enderror
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-md-12">
                                <label for="note" class="col-form-label">{{ __('Expense Note') }}</label>
                                <textarea class="form-control @error('note') is-invalid @enderror" id="note" name="note" placeholder="{{ __('Expense Note') }}">{{ $expense->note }}</textarea>
                                @error('note')
                                <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                @enderror
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 form-group">
                                <label for="expenseAttachment" class="col-form-label">{{ __('Expense Attachment') }}</label>
                                <div class="custom-file">
                                    <input type="file" class="custom-file-input @error('expenseAttachment') is-invalid @enderror" id="attached-image" name="expenseAttachment" accept="image/png, image/jpeg, image/gif, image/svg, image/webp, image/jpg">
                                    <label class="custom-file-label" for="customFile">{{ __('Choose file') }}</label>
                                    <small id="expenseAttachmentHelp" class="form-text text-muted">{{ __('jpg, png, jpeg, svg, webp and gif images are supported as expense attachment') }} </small>
                                    @error('expenseAttachment')
                                    <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                    @enderror
                                </div>
                                <div class="image-preview">
                                    @if($expense->expense_image)
                                        <img src="{{ $expense->imagepath() }}" id="attached-preview-img" class="mt-3"/>
                                    @else
                                        <img src="{{ asset('img/placeholder.png') }}" id="attached-preview-img" class="mt-3"/>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="status" class="col-form-label">{{ __('Status') }}</label>
                                <select class="form-control" id="status" name="status">
                                    <option value="1" {{ $expense->isActive() ? 'selected' : '' }}>{{ __('Active') }}</option>
                                    <option value="0" {{ $expense->isActive() ? '' : 'selected' }}>{{ __('Inactive') }}</option>
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-10">
                                <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> {{ __('Save Changes') }}</button>
                            </div>
                        </div>
                    </div>
                    <!-- /.card-body -->
                </form>
            </div>
            <!-- /.card-body -->
        </div>
    </div>
    <!-- /.content -->
@endsection

@section('extra-script')
<script src="{{ asset('js/select2/select2.min.js') }}"></script>
@endsection


