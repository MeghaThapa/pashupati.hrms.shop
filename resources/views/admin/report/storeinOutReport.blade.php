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
                <h1 class="m-0 text-dark">{{ __('Report') }}</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item">
                        <a href="{{ route('dashboard') }}">{{ __('Dashboard') }}</a>
                    </li>
                    <li class="breadcrumb-item active">{{ __('report') }}</li>
                </ol>
            </div>
        </div>
    </div>
    <!-- /.content-header -->
    <div class="content">
        <div class="container-fluid">
            {{-- <div class="card"> --}}
            {{-- <input type="text" id="nepaliDate" class="date-picker" placeholder="Select Nepali Date" /> --}}
            <div class="table-custom card-body table-responsive">
                <table class="table table-bordered table-hover" id="storeinOutReport">
                    <thead>
                        <tr>
                            <th>SN</th>
                            <th>Item Name</th>
                            <th>Opening Qty</th>
                            <th>Opening Rate</th>
                            <th>Opening Total</th>
                            <th>Purchase Qty</th>
                            <th>Purchase Rate</th>
                            <th>Purchase Total</th>
                            <th>Issued Qty</th>
                            <th>Issued Rate</th>
                            <th>Issued Total</th>
                            <th>Closing Qty</th>
                            <th>Closing Rate</th>
                            <th>Closing Total</th>
                        </tr>
                    </thead>
                    <tbody id="storeinOutReportTbody">

                    </tbody>
                </table>
            </div>
            {{-- </div> --}}
        </div>
    </div>
@endsection
@section('extra-script')
    <script>
        $(document).ready(function() {
            var table = $('#storeinOutReport').DataTable({
                lengthMenu: [
                    [30, 40, 50, -1],
                    ['30 rows', '40 rows', '50 rows', 'Show all']
                ],
                style: 'bootstrap', // Corrected 'style' option
                processing: true,
                serverSide: true,
                ajax: "{{ route('closingStoreinReport.yajraReport') }}",
                error: function(xhr, error, thrown) {
                    console.log("Error fetching data:", error);
                },
                columns: [{
                        data: 'DT_RowIndex'
                    },
                    {
                        data: 'item_name'
                    },
                    {
                        data: 'opening_qty'
                    },
                    {
                        data: 'opening_rate'
                    },
                    {
                        data: 'opening_total'
                    },
                    {
                        data: 'purchase_qty'
                    },
                    {
                        data: 'purchase_rate'
                    },
                    {
                        data: 'purchase_total'
                    },
                    {
                        data: 'issue_qty'
                    },
                    {
                        data: 'issue_rate'
                    },
                    {
                        data: 'issue_total'
                    },
                    {
                        data: 'closing_qty'
                    },
                    {
                        data: 'closing_rate'
                    },
                    {
                        data: 'closing_total'
                    }
                ],



            });
        });
    </script>
@endsection
