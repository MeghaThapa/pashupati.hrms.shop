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
                <h1 class="m-0 text-dark">{{ __('Fabric Entry Report') }}</h1>
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
                    <h3>Fabric Entry Report</h3>
                </div>
                {{-- <input type="text" id="nepaliDate" class="date-picker" placeholder="Select Nepali Date" /> --}}
                <div class="table-custom card-body table-responsive">
                    <table class="table table-bordered table-hover" id="bswTable">
                        <thead>
                            <tr>
                                <th>SN</th>
                                <th>Godam</th>
                                <th>File</th>
                                <th>Entry Date</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('extra-script')
    <script>
        $(document).ready(function() {
            var table = $('#bswTable').DataTable({
                lengthMenu: [
                    [30, 40, 50, -1],
                    ['30 rows', '40 rows', '50 rows', 'Show all']
                ],
                style: 'bootstrap', // Corrected 'style' option
                processing: true,
                serverSide: true,
                ajax: "{{ route('fabric.entry.report.table') }}",
                columns: [
                    { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                    { data: 'godam', name: 'godam' },
                    { data: 'file_path', name: 'file_path' },
                    { data: 'entry_date', name: 'entry_date' },
                ]

            });
        });
    </script>
@endsection