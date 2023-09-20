@extends('layouts.admin')
@section('extra-style')
    <link href="{{ asset('css/nepaliDatePicker/nepali.datepicker.v4.0.1.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('css/select2/select2.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('css/select2/select2-bootstrap4.css') }}" rel="stylesheet" />
@endsection
@section('content')
    <!-- Content Header (Page header) -->
    <div id="toast-container" class="toast-top-right"></div>
    <div class="content-header mb-4">
        <div class="row align-items-center">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">{{ __('opening rawmaterial') }}</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item">
                        <a href="{{ route('dashboard') }}">{{ __('Dashboard') }}</a>
                    </li>
                    <li class="breadcrumb-item">{{ __('opening rawmaterial') }}</li>
                    {{-- <li class="breadcrumb-item active">{{ __('Create Entry') }}</li> --}}
                </ol>
            </div>
        </div>
    </div>
    <!-- /.content-header -->
    <div class="content">
        <div class="container-fluid">
            <div class="card">
                <div class="card-header">
                    <a href="{{ route('openingRawmaterialEntry.index') }}"class='go-back btn btn-primary'>Go Back</a>
                </div>
                <div class="table-custom card-body table-responsive">
                    <form method="post"
                        @if ($rawmaterialOpeningEntryData) action="{{ route('rawMaterialOpening.update', ['openingRawmaterialEntry_id' => $rawmaterialOpeningEntryData->id]) }}"
                        @else
                        action="{{ route('openingRawmaterialEntry.store') }}" @endif>
                        @csrf
                        <div class="row">
                            <div class="col-md-6">
                                <label for="receipt_number">Opening Date</label>
                                <input type="date" name="date_np" class="form-control"
                                    @if ($rawmaterialOpeningEntryData) value="{{ $rawmaterialOpeningEntryData->opening_date }}" @endif>
                            </div>
                            <div class="col-md-6">
                                <label for="receipt_number">Receipt No</label>
                                <input type="text" name="receipt_no" class="form-control" id="receiptNo" value="Opening">
                            </div>
                            <div class="col-md-6">
                                <label for="receipt_number">To Godam</label>
                                <select name="to_godam" id="toGodam" class="advance-select-box form-control">
                                    <option value=" " selected disabled>{{ __('Select godam') }}</option>
                                    @foreach ($godams as $godam)
                                        @if ($rawmaterialOpeningEntryData)
                                            <option value="{{ $godam->id }}"
                                                {{ $rawmaterialOpeningEntryData->godam->id == $godam->id ? 'selected' : null }}>
                                                {{ $rawmaterialOpeningEntryData->godam->name }}
                                            </option>
                                        @else
                                            <option value="{{ $godam->id }}">{{ $godam->name }}</option>
                                        @endif
                                    @endforeach
                                </select>

                            </div>
                            <div class="col-md-6">
                                <label for="receipt_number">Remark</label>
                                <input type="text" name="remark" class="form-control" id="remark"
                                    @if ($rawmaterialOpeningEntryData) value="{{ $rawmaterialOpeningEntryData->remark }}" @endif>
                            </div>

                            <div class="col-md-6 mt-3">
                                @if ($rawmaterialOpeningEntryData)
                                    <button class="btn btn-primary" type="submit">Update</button>
                                @else
                                    <button class="btn btn-primary" type="submit">Create</button>
                                @endif
                            </div>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>
@endsection
@section('extra-script')
    <script src="{{ asset('js/nepaliDatePicker/nepali.datepicker.v4.0.1.min.js') }}"></script>
    <script src="{{ asset('js/select2/select2.min.js') }}"></script>
    @if (session()->has('message'))
        <script>
            toastr.success("{{ session()->get('message') }}");
        </script>
    @elseif(session()->has('message_err'))
        <script>
            toastr.error("{{ session()->get('message_err') }}");
        </script>
    @endif
    <script>
        $(document).ready(function() {

            // $("#nepali-date-picker").nepaliDatePicker({});
            let todayNepaliDate = {!! isset($nepaliDate) ? json_encode($nepaliDate) : 'null' !!};
            if (todayNepaliDate !== null) {
                // $("#nepali-date-picker").val(todayNepaliDate);
            }

            let editObj = {!! json_encode($rawmaterialOpeningEntryData) !!};
            if (editObj === null) {
                if (todayNepaliDate !== null) {
                    // $("#nepali-date-picker").val(todayNepaliDate);
                }
            } else {
                if (editObj.hasOwnProperty('opening_date')) {
                    // $("#nepali-date-picker").val(editObj.opening_date);
                }
            }

        })
    </script>
@endsection
