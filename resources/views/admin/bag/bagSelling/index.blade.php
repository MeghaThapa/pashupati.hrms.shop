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
                <h1 class="m-0 text-dark">{{ __('Bag Selling') }}</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item">
                        <a href="{{ route('dashboard') }}">{{ __('Dashboard') }}</a>
                    </li>
                    <li class="breadcrumb-item active">{{ __('Bag selling') }}</li>
                </ol>
            </div>
        </div>
    </div>
    <!-- /.content-header -->
    <div class="content">
        <div class="container-fluid">
            <div class="card">
                <div class="card-header">
                    <a href="{{ route('bagSelling.create') }}" class='btn btn-primary'>Bag Sales Entry</a>
                </div>
                <div class="table-custom card-body table-responsive">
                    <table class="table table-bordered table-hover" id="bagSellingEntryData">
                        <thead>
                            <tr>
                                <th>SN</th>
                                <th>Challan Number</th>
                                <th>Date</th>
                                <th>Supplier</th>
                                <th>Gp No</th>
                                <th>Lorry No</th>
                                <th>Do no</th>
                                <th>Rem</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('extra-script')
    <script>
        $(document).ready(function() {
            var table = $('#bagSellingEntryData').DataTable({
                lengthMenu: [
                    [30, 40, 50, -1],
                    ['30 rows', '40 rows', '50 rows', 'Show all']
                ],
                style: 'bootstrap4',
                processing: true,
                serverSide: true,
                ajax: "{{ route('bagSelling.bagSellingYajraDatatables') }}",
                columns: [{
                        data: 'DT_RowIndex'
                    },
                    {
                        data: 'challan_no'
                    },
                    {
                        data: 'nepali_date'
                    },
                    {
                        data: 'supplier.name'
                    },
                    {
                        data: 'gp_no'
                    },
                    {
                        data: 'lorry_no'
                    },
                    {
                        data: 'do_no'
                    },
                    {
                        data: 'rem'
                    },
                    {
                        data: 'statusBtn',
                    },
                    {
                        data: 'action',
                        orderable: true,
                        searchable: true,
                    },
                ]
            });
        });
    </script>
    @if (session()->has('message'))
        <script>
            toastr.success("{{ session()->get('message') }}");
        </script>
    @elseif(session()->has('message_err'))
        <script>
            toastr.error("{{ session()->get('message_err') }}");
        </script>
    @endif
@endsection
