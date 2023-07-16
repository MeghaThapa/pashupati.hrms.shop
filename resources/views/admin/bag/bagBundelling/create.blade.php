@extends('layouts.admin')
@section('extra-style')
    <link href="{{ asset('css/nepaliDatePicker/nepali.datepicker.v4.0.1.min.css') }}" rel="stylesheet" type="text/css" />

    <link href="{{ asset('css/select2/select2.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('css/select2/select2-bootstrap4.css') }}" rel="stylesheet" />
@endsection
@section('content')
    <div class="content-header mb-4">
        <div class="row align-items-center">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">{{ __('Bag Bundelling') }}</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item">
                        <a href="{{ route('dashboard') }}">{{ __('Dashboard') }}</a>
                    </li>
                    <li class="breadcrumb-item active">{{ __('Bag Bundelling') }}</li>
                </ol>
            </div>
        </div>
    </div>
    <div class="content">
        <div class="container-fluid">
            <a class='btn btn-primary go-back float-right'>Go back</a>
            <br><br>
            <div class="form">

                <form
                    @if ($bagBundelEntryData) action="{{ route('bagBundelling.update', ['bagBundleEntryId' => $bagBundelEntryData->id]) }}"
                    @else
                    action="{{ route('bagBundelling.store') }}" @endif
                    method="POST">

                    @csrf
                    <div class="card-body">
                        <div id="form-error" class="alert alert-danger" hidden>

                        </div>
                        <div class="row">
                            <div class="col-md-12 form-group">

                                <label for="size" class="col-form-label">{{ __('Receipt Number') }}<span
                                        class="required-field">*</span>
                                </label>
                                <input type="text" step="any" min="0" class="form-control calculator"
                                    id="receiptNo" data-number="1" name="receipt_no"
                                    @if ($bagBundelEntryData) value="{{ $bagBundelEntryData->receipt_no }}" @else
                                    value="{{ $receipt_no }}" @endif
                                    placeholder="{{ __('Receipt No') }}" min="1" required readonly>
                                @error('receipt_no')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 form-group">
                                <label for="size" class="col-form-label">{{ __('Receipt Date') }}
                                </label>
                                <input type="date" step="any" min="0" class="form-control calculator"
                                    id="receiptDate" data-number="1" name="receipt_date"
                                    @if ($bagBundelEntryData) value="{{ $bagBundelEntryData->receipt_date }}" @endif
                                    placeholder="{{ __('Receipt Date') }}" min="1" required>
                                @error('receipt_date')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 form-group">
                                <label for="size" class="col-form-label">{{ __('Neapli Date') }}
                                </label>
                                <input type="text" class="form-control" name="nepali_date" id="nepali-date-picker">
                            </div>
                        </div>
                    </div>
                    @if ($bagBundelEntryData)
                        <button type="submit" class="btn btn-primary">Update Bag Bundel</button>
                    @else
                        <button type="submit" class="btn btn-primary">Craete Bag Bundel</button>
                    @endif

                </form>
            </div>
        </div>
    </div>
@endsection
@section('extra-script')
    <script src="{{ asset('js/nepaliDatePicker/nepali.datepicker.v4.0.1.min.js') }}"></script>
    <script>
        $(document).ready(function() {

            $("#nepali-date-picker").nepaliDatePicker({});

            let editObj = {!! json_encode($bagBundelEntryData) !!};
            let todayNepaliDate = {!! isset($nepaliDate) ? json_encode($nepaliDate) : 'null' !!};
            if (editObj === null) {
                if (todayNepaliDate !== null) {
                    $("#nepali-date-picker").val(todayNepaliDate);
                }
            } else {
                if (editObj.hasOwnProperty('nepali_date')) {
                    $("#nepali-date-picker").val(editObj.nepali_date);
                }
            }
        })
    </script>
@endsection
