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
                <h1 class="m-0 text-dark">{{ __('Prints and Cuts') }}</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item">
                        <a href="{{ route('dashboard') }}">{{ __('Dashboard') }}</a>
                    </li>
                    <li class="breadcrumb-item">{{ __('Prints and cuts') }}</li>
                    <li class="breadcrumb-item active">{{ __('Create Entry') }}</li>
                </ol>
            </div>
        </div>
    </div>
    <!-- /.content-header -->
    <div class="content">
        <div class="container-fluid">
            <div class="card">
                <div class="card-header">
                    <a href="{{ route('bagSelling.index') }}"class='go-back btn btn-primary'>Go Back</a>
                </div>
                <div class="table-custom card-body table-responsive">
                    <form
                        @if ($bagSellingEntryData) action="{{ route('bagSellingEntry.update', ['bagSellingEntry_id' => $bagSellingEntryData->id]) }}"
                        @else
                        action="{{ route('bagSelling.store') }}" @endif
                        method="post">
                        @csrf
                        <div class="row">
                            <div class="col-md-6">
                                <label for="receipt_number">Challan No</label>
                                <input type="text" name="challan_no" class="form-control" id="challanNo"
                                    @if ($bagSellingEntryData) value="{{ $bagSellingEntryData->challan_no }}" @endif>
                            </div>
                            <div class="col-md-6">
                                <label for="receipt_number">Date</label>
                                <input type="text" name="date" class="form-control"
                                    @if ($bagSellingEntryData) value="{{ $bagSellingEntryData->date }}" @endif
                                    id="date">
                            </div>
                            <div class="col-md-6">
                                <label for="receipt_number">Date Np</label>
                                <input type="text" name="date_np" class="form-control"
                                    @if ($bagSellingEntryData) value="{{ $bagSellingEntryData->nepali_date }}" @endif
                                    id="nepali-date-picker">
                            </div>
                            <div class="col-md-6">
                                <label for="receipt_number">Supplier</label>
                                <select name="supplier_id" id="supplierId" class="advance-select-box form-control">
                                    <option value=" " selected disabled>{{ __('Select group') }}</option>
                                    @foreach ($suppliers as $supplier)
                                        <option @if ($bagSellingEntryData && $bagSellingEntryData->supplier->id == $supplier->id) selected @endif
                                            value="{{ $supplier->id }}">
                                            {{ $supplier->name }}
                                        </option>
                                    @endforeach

                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="receipt_number">GP NO</label>
                                <input type="text" name="gp_no" class="form-control"
                                    @if ($bagSellingEntryData) value="{{ $bagSellingEntryData->gp_no }}" @endif
                                    id="gpNo">
                            </div>
                            <div class="col-md-6">
                                <label for="receipt_number">Lorry No</label>
                                <input type="text" name="lorry_no" class="form-control"
                                    @if ($bagSellingEntryData) value="{{ $bagSellingEntryData->lorry_no }}" @endif
                                    id="lorryNo">
                            </div>
                            <div class="col-md-6">
                                <label for="receipt_number">DO NO</label>
                                <input type="text" name="do_no" class="form-control"
                                    @if ($bagSellingEntryData) value="{{ $bagSellingEntryData->do_no }}" @endif
                                    id="doNo">
                            </div>
                            <div class="col-md-6">
                                <label for="receipt_number">Rem</label>
                                <input type="text" name="rem" class="form-control"
                                    @if ($bagSellingEntryData) value="{{ $bagSellingEntryData->rem }}" @endif
                                    id="rem">
                            </div>
                            <div class="col-md-6 mt-3">
                                @if ($bagSellingEntryData)
                                    <button class="btn btn-primary" type="submit">Update </button>
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

            $("#nepali-date-picker").nepaliDatePicker({});
            let todayNepaliDate = {!! isset($nepaliDate) ? json_encode($nepaliDate) : 'null' !!};
            if (todayNepaliDate !== null) {
                $("#nepali-date-picker").val(todayNepaliDate);
            }
            {{--  let editObj = {!! json_encode($bagBundelEntryData) !!};

            if (editObj === null) {
                if (todayNepaliDate !== null) {
                    $("#nepali-date-picker").val(todayNepaliDate);
                }
            } else {
                if (editObj.hasOwnProperty('nepali_date')) {
                    $("#nepali-date-picker").val(editObj.nepali_date);
                }
            }
            --}}
        })
    </script>
@endsection
