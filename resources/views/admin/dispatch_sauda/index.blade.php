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
                <h4><strong>Dispatch Sauda Item to Party Entry</strong></h4>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item">
                        <a href="{{ route('dashboard') }}">{{ __('Dashboard') }}</a>
                    </li>
                    <li class="breadcrumb-item active">{{ __('Dispatch Sauda Item to Party Entry') }}</li>
                </ol>
            </div>
        </div>
    </div>
    <!-- /.content-header -->
    <div class="content">
        <div class="container-fluid">
            <div class="card mt-3">
                <div class="card-header">
                    <h4>Dispatch Sauda Item to Party Entry</h4>
                </div>
                <div class="card-body">
                    @if ($errors->any())
                        <div class="border-rounded border-danger alert alert-light text-danger text-center alert-dismissible fade show"
                            role="alert">
                            <button type="button" class="close" data-dismiss="alert" text-danger aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                                <span class="sr-only">Close</span>
                            </button>
                            @foreach ($errors->all() as $error)
                                {!! $error . '<br>' !!}
                            @endforeach
                        </div>
                    @endif
                    <form action="{{ route('dispatch-sauda-item.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="row">
                            <div class="col-sm-6">
                                <!-- Delivery Order Number -->
                                <div class="form-group">
                                    <label for="sauda_item_id">Sauda Number:</label>
                                    <select id="sauda_item_id" name="sauda_item_id" class="form-control select2">
                                        <option value="" selected disabled>Select Sauda No</option>
                                        @foreach($saudaItems as $saudaItem)
                                            <option value="{{ $saudaItem->id }}">{{ $saudaItem->sauda_no }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- Delivery Order Date -->
                                <div class="form-group">
                                    <label for="dispatch_date">Dispatch Date:</label>
                                    <input type="date" id="dispatch_date" name="dispatch_date" required class="form-control">
                                </div>

                                <div class="form-group">
                                    <label for="sauda_for">Sauda For:</label>
                                    <input type="text" class="form-control" id="sauda_for" readonly />
                                </div>

                                <!-- Supplier ID -->
                                <div class="form-group">
                                    <label for="supplier_id">Supplier</label>
                                    <input id="supplier_id" type="text" class="form-control" name="supplier_id" value="" readonly />
                                </div>

                                <div class="form-group">
                                    <label for="party_acc">Party Account:</label>
                                    <input type="text" id="party_acc" name="party_acc"
                                        class="form-control" required>
                                </div>

                            </div>

                            <div class="col-sm-6">

                                <div class="form-group">
                                    <label for="available_qty">Available Quantity:</label>
                                    <input type="text" id="available_qty" name="available_qty" readonly
                                        class="form-control decimal_number">
                                </div>


                                <div class="form-group">
                                    <label for="dispatch_qty">Dispatch Quantity:</label>
                                    <input type="text" id="dispatch_qty" name="dispatch_qty" required
                                        class="form-control decimal_number">
                                </div>

                                <!-- Delivery Order For Item ID -->
                                <div class="form-group">
                                    <label for="delivery_order_for_item_id"> For Item</label>
                                    <input type="text" class="form-control" id="delivery_order_for_item_id" name="delivery_order_for_item_id" readonly />
                                </div>

                                <div class="form-group">
                                    <label for="fabric_name">Fabric Name</label>
                                    <input id="fabric_name" type="text" name="fabric_name" class="form-control" readonly />
                                </div>

                                <div class="form-group">
                                    <label for="unit_name">Unit Name:</label>
                                    <input type="text" id="unit_name" name="unit_name"
                                        class="form-control" readonly>
                                </div>

                                <div class="form-group">
                                    <label for="rate">Rate:</label>
                                    <input type="text" id="rate" name="rate" class="form-control decimal_number" readonly>
                                </div>

                            </div>
                        </div>

                        <!-- Pending Sauda -->
                        <div class="form-group">
                            <label for="remarks">Remarks:</label>
                            <textarea id="remarks" name="remarks" rows="4" class="form-control"></textarea>
                        </div>

                        <!-- Submit Button -->
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </div>
                    </form>

                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <div class="row" style="width: 100%;">
                        <div class="col-sm-6">
                            <h4>Dispatch Sauda Item to Party Entries </h4>
                        </div>
                        <div class="col-sm-6">
                            <a class="btn btn-success" href="{{ route('dispatch.item.datewise.filter') }}"> Datewise
                                Sauda Order Report</a>
                        </div>
                    </div>
                </div>
                <div class="card-body table-responsive">
                    <table class="table table-bordered table-hover table-striped" id="myTable">
                        <thead>
                            <tr>
                                <th>SN</th>
                                <th>Sauda No</th>
                                <th>Dispatch Date</th>
                                <th>For</th>
                                <th>Supplier</th>
                                <th>Party Account</th>
                                <th>For item</th>
                                <th>Fabric Name</th>
                                <th>Dispatch Qty</th>
                                <th>Unit</th>
                                <th>Rate</th>
                                <th>Remarks</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div id="deleteModal" class="modal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header bg-danger">
                    <h5 class="modal-title">Are you sure you want to delete this entry?</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>Your transactions from this entry would be rolled back...</p>
                </div>
                <div class="modal-footer">
                    <input type="hidden" id="deleteID" />
                    <button type="button" class="btn btn-danger confirm_remove">Delete</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
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
    <script>
        $(document).ready(function() {

            let table = $("#myTable").DataTable({
                serverSide: true,
                processing: true,
                lengthMenu: [
                    [10, 25, 50, 100, 250, 500],
                    [10, 25, 50, 100, 250, 500]
                ],
                ajax: {
                    url: "{{ route('dispatch-sauda-item.index') }}",
                    data: function(data) {
                        // data.start_date = $('#start_date').val();
                        // data.end_date = $('#end_date').val();
                    },
                },
                columns: [{
                        name: "DT_RowIndex",
                        data: "DT_RowIndex",
                        orderable: false,
                    },
                    {
                        name: "sauda_no",
                        data: "sauda_no"
                    },
                    {
                        name: "dispatch_date",
                        data: "dispatch_date"
                    },
                    {
                        name: "for",
                        data: "for"
                    },
                    {
                        name: "supplier_id",
                        data: "supplier_id"
                    },
                    {
                        name: "party_acc",
                        data: "party_acc"
                    },
                    {
                        name: "delivery_order_for_item_id",
                        data: "delivery_order_for_item_id"
                    },
                    {
                        name: "fabric_name",
                        data: "fabric_name"
                    },
                    {
                        name: "dispatch_qty",
                        data: "dispatch_qty"
                    },
                    {
                        name: "unit_name",
                        data: "unit_name"
                    },
                    {
                        name: "rate",
                        data: "rate"
                    },
                    {
                        name: "remarks",
                        data: "remarks"
                    },
                    {
                        name: "action",
                        data: "action"
                    },
                ]
            });

            $('#sauda_item_id').on('change',function(){
                let id = $(this).val();
                let url = "{{ route('sauda-item.index') }}/"+id;
                $.ajax({
                    url: url,
                    method: "GET",
                    data: {
                        _token: "{{ csrf_token() }}",
                    },
                    success: function(response) {
                        if(response.status==true){

                            $('#delivery_order_for_item_id').val(response.data.delivery_order_for_item.name);
                            $('#sauda_for').val(response.data.sauda_for);
                            $('#fabric_name').val(response.data.fabric_name);
                            $('#supplier_id').val(response.data.supplier.name);
                            $('#unit_name').val(response.data.unit_name);
                            $('#rate').val(response.data.rate);
                            $('#available_qty').val(response.data.qty);

                        }else{
                            console.log(response);
                        }
                    },
                    error: function(error) {
                        console.log(error);
                    }
                });

            });


        });

        function isValidDecimal(input) {
            const decimalPattern = /^\d+(\.\d+)?$/;
            return decimalPattern.test(input);
        }

        $(document).on('keyup', '.decimal_number', function() {
            const inputValue = $(this).val();
            if (isValidDecimal(inputValue)) {
                $(this).removeClass('invalid').addClass('valid');
            } else {
                $(this).removeClass('valid').addClass('invalid');
            }
        });
    </script>
@endsection
