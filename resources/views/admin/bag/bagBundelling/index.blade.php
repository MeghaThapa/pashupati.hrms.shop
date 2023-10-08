@extends('layouts.admin')
@section('extra-style')
    <link href="{{ asset('css/nepaliDatePicker/nepali.datepicker.v4.0.1.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('css/select2/select2.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('css/select2/select2-bootstrap4.css') }}" rel="stylesheet" />
@endsection
@section('content')
    <!-- Content Header (Page header) -->
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
    <!-- /.content-header -->
    <div class="content">
        <div class="container-fluid">
            <div class="card">
                <div class="card-header">
                    {{-- <button class="btn btn-primary" id="createBagBundel">Create Bag Bundelling</button> --}}
                    <a href="{{ route('bagBundelling.createBagBundleEntry') }}" class='btn btn-primary'>Create Bag
                        Bundelling</a>
                </div>
                {{-- <input type="text" id="nepaliDate" class="date-picker" placeholder="Select Nepali Date" /> --}}
                <div class="table-custom card-body table-responsive">
                    <table class="table table-bordered table-hover ">
                        <thead>
                            <tr>
                                <th>SN</th>
                                <th>Receipt Number</th>
                                <th>Receipt Date</th>
                                <th>Nepali Date</th>
                                <th>total_bundle_quantity</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($bagBundelEntries as $index => $bagBundelEntry)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $bagBundelEntry->receipt_no }}</td>
                                    <td>{{ $bagBundelEntry->receipt_date }}</td>
                                    <td>{{ $bagBundelEntry->nepali_date }}</td>
                                    <td>{{ $bagBundelEntry->total_bundle_quantity }}</td>
                                    {{-- <td>{{ $bagBundelEntry->average_weight }}</td> --}}
                                    <td>
                                        @if ($bagBundelEntry->status == 'running')
                                            <div class="btn-group">
                                                <a class="btn btn-primary"
                                                    href="{{ route('bagBundelling.edit', ['bagBundelEntry_id' => $bagBundelEntry->id]) }}">
                                                    <i class="fas fa-edit"></i> </a>

                                            </div>
                                        @elseif($bagBundelEntry->status == 'completed')
                                            <a class="btn btn-success"
                                                href="{{ route('bagBundelling.view', ['bagBundelEntry_id' => $bagBundelEntry->id]) }}">
                                                <i class="fa fa-eye" aria-hidden="true"></i> </a>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    {{-- create bag bundelling --}}
    <div class="modal fade" id="createBagBundleEntry" tabindex="-1" role="dialog" aria-labelledby="tryModelLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="tryModelLabel">Create Bag Bundle Entry</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="createBagBundleEntryModel">
                    <div class="modal-body">
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
                                        placeholder="{{ __('Receipt No') }}" min="1" required>
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
                                    <input type="text" id="nepali-date-picker">
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Craete AutoLoad</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
@section('extra-script')
    <script>
        $(document).ready(function() {


        });
    </script>
@endsection
