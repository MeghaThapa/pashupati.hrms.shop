@extends('layouts.admin')

@section('extra-style')
    <link href="{{ asset('css/select2/select2.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('css/select2/select2-bootstrap4.css') }}" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.css"
        integrity="sha512-3pIirOrwegjM6erE5gPSwkUzO+3cTjpnV9lexlNZqvupR64iZBnOOTiiLPb9M36zpMScbmUNIcHUqKD47M719g=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link href="{{ asset('css/nepaliDatePicker/nepali.datepicker.v4.0.1.min.css') }}" rel="stylesheet" type="text/css" />
    <style>
        .update_status {
            cursor: pointer;
        }

        .invalid {
            border-color: red;
        }

        .valid {
            border-color: green;
        }
    </style>
@endsection

@section('content')
    <!-- Content Header (Page header) -->
    <div class="content-header mb-4">
        <div class="row align-items-center">
            <div class="col-sm-6 mt-2">
                <h4><strong>Delivery Order - Sale Details</strong></h4>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item">
                        <a href="{{ route('dashboard') }}">{{ __('Dashboard') }}</a>
                    </li>
                    <li class="breadcrumb-item active">{{ __('Delivery Order') }}</li>
                </ol>
            </div>
        </div>
    </div>
    <!-- /.content-header -->
    <div class="content">
        <div class="container-fluid">

            <div class="card">
                <div class="card-header">
                    <div class="row" style="width: 100%;">
                        <div class="col-sm-4">
                            <h4>Delivery Order Entries</h4>
                        </div>
                        <div class="col-sm-4">
                            <a class="btn btn-success" href="{{ route('delivery.order.datewise.filter') }}"> Datewise
                                Delivery Order Report</a>
                        </div>
                    </div>
                </div>
                <div class="card-body table-responsive">
                    <table class="table table-bordered table-hover table-striped" id="myTable">
                        <thead>
                            <tr>
                                <th>DO Number</th>
                                <th>{{ $deliveryOrder->do_no }}</th>
                            </tr>
                            <tr>
                                <th>DO Date</th>
                                <th>{{ $deliveryOrder->do_date }}</th>
                            </tr>
                            <tr>
                                <th>Supplier</th>
                                <th>{{ $deliveryOrder->supplier->name }}</th>
                            </tr>
                            <tr>
                                <th>Overdue Amount</th>
                                <th>{{ $deliveryOrder->overdue_amount }}</th>
                            </tr>
                            <tr>
                                <th>Total Due</th>
                                <th>{{ $deliveryOrder->total_due }}</th>
                            </tr>
                            <tr>
                                <th>Party Limit</th>
                                <th>{{ $deliveryOrder->party_limit }}</th>
                            </tr>
                            <tr>
                                <th>For item</th>
                                <th>{{ $deliveryOrder->deliveryOrderForItem->name }}</th>
                            </tr>
                            <tr>
                                <th>Qty in MT</th>
                                <th>{{ $deliveryOrder->qty_in_mt }}</th>
                            </tr>
                            <tr>
                                <th>Bundel PCS</th>
                                <th>{{ $deliveryOrder->bundel_pcs }}</th>
                            </tr>
                            <tr>
                                <th>Base Rate/Kg</th>
                                <th>{{ $deliveryOrder->base_rate_per_kg }}</th>
                            </tr>
                            <tr>
                                <th>Collection</th>
                                <th>{{ $deliveryOrder->collection }}</th>
                            </tr>
                            <tr>
                                <th>Pending Sauda</th>
                                <th>{{ $deliveryOrder->pending_sauda }}</th>
                            </tr>
                            <tr>
                                <th>Status</th>
                                <th>{{ $deliveryOrder->status }}</th>
                            </tr>
                        </thead>
                    </table>
                </div>
                <div class="card-body">
                    <h6>Fabric Sales Details</h6>
                    <table class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>Invoice No</th>
                                <th>Bill For</th>
                                <th>Lorry No</th>
                                <th>GP No</th>
                                <th>Remarks</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($fabricSaleEntries as $fabricSaleEntry)
                            <tr>
                                <td>{{ $fabricSaleEntry->bill_no }}</td>
                                <td>{{ $fabricSaleEntry->bill_for }}</td>
                                <td>{{ $fabricSaleEntry->lorry_no }}</td>
                                <td>{{ $fabricSaleEntry->gp_no }}</td>
                                <td>{{ $fabricSaleEntry->remarks }}</td>
                                <td>{{ $fabricSaleEntry->status }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('extra-script')
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"
        integrity="sha512-VEd+nq25CkR676O+pLBnDW09R7VQX9Mdiij052gVCp5yVH3jGtH70Ho/UUv4mJDsEdTvqRCFZg0NKGiojGnUCw=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    <script src="{{ asset('js/select2/select2.min.js') }}"></script>
    <script src="{{ asset('js/nepaliDatePicker/nepali.datepicker.v4.0.1.min.js') }}"></script>
    <script>
        $(".select2").select2()
    </script>
    @if (session()->has('message'))
        <script>
            toastr.success('{{ session()->get('message') }}');
        </script>
    @endif
@endsection
